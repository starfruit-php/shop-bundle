<?php

namespace Starfruit\ShopBundle\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

use Pimcore\Bundle\EcommerceFrameworkBundle\Factory;
use Starfruit\ShopBundle\Controller\ShopCartBaseController;

class CartExtension extends AbstractExtension
{
    protected $factory;

    public function __construct(
        Factory $factory,
    )
    {
        $this->factory = $factory;
    }

    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('shop_cart_getlist', [$this, 'getCart']),
        ];
    }

    public function getCart()
    {
        $cartManager = $this->factory->getCartManager();
        return $cartManager->getOrCreateCartByName(ShopCartBaseController::DEFAULT_CART_NAME);
    }
}
