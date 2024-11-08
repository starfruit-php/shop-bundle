# Helper functions

## Product

### Get list in template

```bash
    {% set items = shop_product_listing() %}
```

### Using ecommerce filter

```bash
    {% set filterDefinitionItems = items.filterDefinition.filters.items %}
    {% for filter in filterDefinitionItems %}
        {% set filterMarkup = items.filterService.filterFrontend(filter, items.productListing, items.currentFilter) %}

        {{ filterMarkup|raw  }}
    {% endfor %}
```

## Cart

### Get list in template

```bash
    {% set cart = shop_cart_getlist() %}
```

### Setup functions with base controller

```bash
    <?php

    namespace App\Controller;

    use Symfony\Component\Routing\Annotation\Route;
    use Starfruit\ShopBundle\Controller\ShopCartBaseController;

    class CartController extends ShopCartBaseController
    {
        const REMOVE_TOKEN_NAME = 'shop-cart-remove';

        protected function getRemoveTokenName(): string
        {
            return self::REMOVE_TOKEN_NAME;
        }

        /**
         * @Route("/cart", name="cart-default")
         */
        public function defaultAction()
        {
            $cart = $this->listing();
            return $this->render('cart/default.html.twig', compact('cart'));
        }

        /**
         * @Route("/cart/add", name="cart-add")
         */
        public function addAction()
        {
            $cart = $this->add();
            return $this->redirectToRoute('cart-default');
        }

        /**
         * @Route("/cart/remove", name="cart-remove")
         */
        public function removeAction()
        {
            $cart = $this->remove();
            return $this->redirectToRoute('cart-default');
        }
    }
```