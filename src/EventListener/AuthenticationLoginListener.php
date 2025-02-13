<?php

namespace Starfruit\ShopBundle\EventListener;

use CustomerManagementFrameworkBundle\Model\CustomerInterface;
use Pimcore\Bundle\EcommerceFrameworkBundle\CartManager\CartInterface;
use Pimcore\Bundle\EcommerceFrameworkBundle\EnvironmentInterface;
use Pimcore\Bundle\EcommerceFrameworkBundle\Factory;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\PasswordUpgradeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

/**
 * Authentication listener to set correct user to e-commerce framework environment after login and track login activity
 */
class AuthenticationLoginListener implements EventSubscriberInterface, LoggerAwareInterface
{
    const DEFAULT_CART_NAME = 'cart';

    use LoggerAwareTrait;

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccess',
        ];
    }

    public function __construct(
        protected EnvironmentInterface $environment,
        protected Factory $factory)
    {
    }

    /**
     * This is called when an interactive authentication attempt succeeds. This
     * is called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param LoginSuccessEvent $event
     *
     * @return void
     */
    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $passport = $event->getPassport();
        if (!$passport instanceof Passport || !$passport->hasBadge(PasswordUpgradeBadge::class)) {
            return;
        }

        $user = $passport->getUser();
        if (null === $user->getPassword() || !$user instanceof CustomerInterface) {
            return;
        }

        // save current user to e-commerce framework environment
        $this->doEcommerceFrameworkLogin($user);
    }

    public function doEcommerceFrameworkLogin(CustomerInterface $customer)
    {
        if ($customer) {
            //migrate current cart entries to cart of to-log-in users cart
            $cartManager = $this->factory->getCartManager();
            $oldCart = $cartManager->getCartByName(self::DEFAULT_CART_NAME);

            $this->environment->setCurrentUserId($customer->getId());

            $cartManager->reset();

            if ($oldCart instanceof CartInterface && count($oldCart->getItems()) > 0) {
                $userCart = $this->factory->getCartManager()->getOrCreateCartByName(self::DEFAULT_CART_NAME);
                foreach ($oldCart->getItems() as $item) {
                    $userCart->addItem($item->getProduct(), $item->getCount(), $item->getItemKey(), false, [], [], $item->getComment());
                }
                $userCart->save();
            }
        } else {
            $this->environment->setCurrentUserId(-1);
        }

        $this->environment->save();
    }
}
