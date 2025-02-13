<?php

namespace Starfruit\ShopBundle\Controller;

use Symfony\Component\HttpFoundation\RequestStack;
use Pimcore\Translation\Translator;
use Pimcore\Bundle\EcommerceFrameworkBundle\Factory;
use Pimcore\Bundle\EcommerceFrameworkBundle\Model\CheckoutableInterface;

use Starfruit\ShopBundle\Model\AbstractProduct;

class ShopCartBaseController extends BaseController
{
    const DEFAULT_CART_NAME = 'cart';
    const CSRF_TOKEN_NAME = '_csrf_token';
    const ADD_TOKEN_NAME = 'shop-cart-add';
    const UPDATE_TOKEN_NAME = 'shop-cart-listing';
    const REMOVE_TOKEN_NAME = 'shop-cart-remove';
    const CLEAR_TOKEN_NAME = 'shop-cart-clear';

    protected $request;
    protected $factory;
    protected $translator;

    public function __construct(
        RequestStack $requestStack,
        Factory $factory,
        Translator $translator,
    )
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->factory = $factory;
        $this->translator = $translator;
    }

    protected function getAddTokenName(): string
    {
        return self::ADD_TOKEN_NAME;
    }

    protected function getUpdateTokenName(): string
    {
        return self::UPDATE_TOKEN_NAME;
    }

    protected function getRemoveTokenName(): string
    {
        return self::REMOVE_TOKEN_NAME;
    }

    protected function getClearTokenName(): string
    {
        return self::CLEAR_TOKEN_NAME;
    }

    protected function getCsrfTokenName(): string
    {
        return self::CSRF_TOKEN_NAME;
    }

    protected function getCart()
    {
        $cartManager = $this->factory->getCartManager();
        return $cartManager->getOrCreateCartByName(self::DEFAULT_CART_NAME);
    }

    public function add()
    {
        if (!$this->isCsrfTokenValid(
            $this->getAddTokenName(),
            $this->request->request->get($this->getCsrfTokenName())
        )) {
            return;
        }

        $id = (int) $this->request->get('id');
        $product = AbstractProduct::getById($id);

        if (null === $product) {
            return;
        }

        $cart = $this->getCart();
        if ($cart->getItemCount() > 99) {
            return;
        }

        $quantity = (int) $this->request->get('quantity');
        $quantity = $quantity > 0 ? $quantity : 1;

        $itemKey = $this->request->get('itemKey');
        $comment = $this->request->get('comment');

        $cart->addItem($product, $quantity, $itemKey, false, [], [], $comment);
        $cart->save();
    }

    public function listing()
    {
        $cart = $this->getCart();

        if ($this->request->getMethod() == $this->request::METHOD_POST) {
            if (!$this->isCsrfTokenValid(
                $this->getUpdateTokenName(),
                $this->request->request->get($this->getCsrfTokenName())
            )) {
                return;
            }

            $items = $this->request->get('items');

            foreach ($items as $item) {
                $itemKey = $item['key'];
                $id = isset($item['id']) ? $item['id'] : $itemKey;
                $quantity = $item['quantity'];

                if (!is_numeric($quantity)) {
                    continue;
                }

                if ($cart->getItemCount() > 99) {
                    break;
                }

                $product = AbstractProduct::getById($id);
                if ($product instanceof CheckoutableInterface) {
                    $cart->updateItem($itemKey, $product, floor($quantity), true);
                }
            }
            $cart->save();
        }

        return $cart;
    }

    public function remove()
    {
        if (!$this->isCsrfTokenValid(
            $this->getRemoveTokenName(),
            $this->request->request->get($this->getCsrfTokenName())
        )) {
            return;
        }

        $key = $this->request->get('key');
        if (!$key) {
            return;
        }

        $cart = $this->getCart();
        $cart->removeItem($key);
        $cart->save();
    }

    public function clear()
    {
        if (!$this->isCsrfTokenValid(
            $this->getClearTokenName(),
            $this->request->request->get($this->getCsrfTokenName())
        )) {
            return;
        }

        $cart = $this->getCart();
        $cart->clear();
        $cart->save();
    }

    public function applyVoucherAction()
    {
        
    }

    public function removeVoucher()
    {
        
    }
}
