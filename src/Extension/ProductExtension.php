<?php

namespace Starfruit\ShopBundle\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

use Symfony\Component\HttpFoundation\RequestStack;
use Pimcore\Bundle\EcommerceFrameworkBundle\Factory;
use Pimcore\Bundle\EcommerceFrameworkBundle\FilterService\ListHelper;
use Knp\Component\Pager\PaginatorInterface;
use Pimcore\Translation\Translator;
use Starfruit\ShopBundle\Service\ProductService;

class ProductExtension extends AbstractExtension
{
    protected $request;
    protected $ecommerceFactory;
    protected $listHelper;
    protected $paginator;
    protected $translator;

    public function __construct(
        RequestStack $requestStack,
        Factory $ecommerceFactory,
        ListHelper $listHelper,
        PaginatorInterface $paginator,
        Translator $translator,
        )
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->ecommerceFactory = $ecommerceFactory;
        $this->listHelper = $listHelper;
        $this->paginator = $paginator;
        $this->translator = $translator;
    }

    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('shop_product_listing', [$this, 'listing']),
        ];
    }

    public function listing($customParams = [])
    {
        $params = ProductService::getList($this->request, $this->ecommerceFactory, $this->listHelper, $this->paginator, $customParams);

        return $params;
    }
}
