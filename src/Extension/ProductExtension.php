<?php

namespace Starfruit\ShopBundle\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

use Symfony\Component\HttpFoundation\RequestStack;
use Knp\Component\Pager\PaginatorInterface;
use Pimcore\Translation\Translator;
use Pimcore\Bundle\EcommerceFrameworkBundle\Factory;
use Pimcore\Bundle\EcommerceFrameworkBundle\FilterService\ListHelper;

class ProductExtension extends AbstractExtension
{
    protected $request;
    protected $translator;
    protected $paginator;
    protected $ecommerceFactory;
    protected $listHelper;

    public function __construct(
        RequestStack $requestStack,
        Translator $translator,
        PaginatorInterface $paginator,
        Factory $ecommerceFactory,
        ListHelper $listHelper
        )
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->translator = $translator;
        $this->paginator = $paginator;
        $this->ecommerceFactory = $ecommerceFactory;
        $this->listHelper = $listHelper;
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

    public function listing()
    {
        $params = array_merge($this->request->query->all(), $this->request->attributes->all());

        //needed to make sure category filter filters for active category
        $params['parentCategoryIds'] = $params['category'] ?? null;

        $category = \Starfruit\ShopBundle\Model\AbstractCategory::getById($params['category'] ?? -1);
        $params['category'] = $category;
        if ($category) {
        }

        $indexService = $this->ecommerceFactory->getIndexService();
        $productListing = $indexService->getProductListForCurrentTenant();
        $params['productListing'] = $productListing;

        // load current filter
        if ($category) {
            $filterDefinition = $category->getFilterdefinition();
        }

        if ($this->request->get('filterdefinition') instanceof \Pimcore\Model\DataObject\FilterDefinition) {
            $filterDefinition = $this->request->get('filterdefinition');
        }

        if (empty($filterDefinition)) {
            $filterDefinition = \Pimcore\Config::getWebsiteConfig()['fallbackFilterdefinition'];
        }

        $filterService = $this->ecommerceFactory->getFilterService();
        $this->listHelper->setupProductList($filterDefinition, $productListing, $params, $filterService, true);
        $params['filterService'] = $filterService;
        $params['filterDefinition'] = $filterDefinition;

        $paginator = $this->paginator->paginate(
            $productListing,
            $this->request->get('page', 1),
            $filterDefinition->getPageLimit()
        );

        $params['results'] = $paginator;
        $params['paginationVariables'] = $paginator->getPaginationData();

        return $params;
    }
}
