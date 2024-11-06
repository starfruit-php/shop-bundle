<?php

namespace Starfruit\ShopBundle\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Pimcore\Translation\Translator;
use Pimcore\Bundle\EcommerceFrameworkBundle\Factory;
use Pimcore\Bundle\EcommerceFrameworkBundle\FilterService\ListHelper;
use Pimcore\Model\DataObject\FilterDefinition;

use Starfruit\ShopBundle\Model\AbstractCategory;
use Starfruit\BuilderBundle\Tool\SystemTool;

class ProductBaseController extends BaseController
{
    protected $request;
    protected $translator;
    protected $paginator;
    protected $ecommerceFactory;
    protected $listHelper;

    public function __construct(
        Translator $translator,
        PaginatorInterface $paginator,
        Factory $ecommerceFactory,
        ListHelper $listHelper
        )
    {
        $this->request = SystemTool::getRequest();
        $this->translator = $translator;
        $this->paginator = $paginator;
        $this->ecommerceFactory = $ecommerceFactory;
        $this->listHelper = $listHelper;
    }

    public function getList()
    {
        $params = array_merge($this->request->query->all(), $this->request->attributes->all());

        //needed to make sure category filter filters for active category
        $params['parentCategoryIds'] = $params['category'] ?? null;

        $category = AbstractCategory::getById($params['category'] ?? -1);
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

        if ($this->request->get('filterdefinition') instanceof FilterDefinition) {
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
