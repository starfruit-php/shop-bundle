<?php

namespace Starfruit\ShopBundle\EventListener;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerAwareInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Pimcore\Bundle\EcommerceFrameworkBundle\Environment;
use Pimcore\Bundle\EcommerceFrameworkBundle\EnvironmentInterface;
use Pimcore\Bundle\EcommerceFrameworkBundle\EventListener\SessionBagListener;

/**
 * Authentication listener to cleanup user and session in general from e-commerce framework environment after logout
 */
class AuthenticationLogoutListener implements EventSubscriberInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

     public static function getSubscribedEvents(): array
    {
        return [
            LogoutEvent::class => 'onLogout',
        ];
    }

    public function __construct(
        protected EnvironmentInterface $environment,
        protected SessionBagListener $sessionBagListener
    ) {
    }

    public function onLogout(LogoutEvent $event): void
    {
        $request = $event->getRequest();

        // unset user in environment
        $this->environment->setCurrentUserId(Environment::USER_ID_NOT_SET);
        $this->environment->save();

        // clear complete e-commerce framework session
        $this->sessionBagListener->clearSession($request->getSession());
    }
}
