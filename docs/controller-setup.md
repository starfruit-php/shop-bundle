# Step by step to setup controller

## Product

### Get list

```bash
    <?php

    namespace App\Controller;

    use Symfony\Component\Routing\Annotation\Route;
    use Starfruit\ShopBundle\Controller\ProductBaseController;

    class ProductController extends ProductBaseController
    {
        /**
         * @Route("/product/listing")
         */
        public function listingAction()
        {
            // get product data
            $params = $this->getList();
            ...
        }
    }
```