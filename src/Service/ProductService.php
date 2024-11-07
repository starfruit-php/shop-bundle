<?php

namespace Starfruit\ShopBundle\Service;

class ProductService
{
    const DEFAULT_LIMIT = 10;

    public static function getList(
        $request,
        $ecommerceFactory,
        $listHelper,
        $paginator,
    )
    {
        $params = array_merge($request->query->all(), $request->attributes->all());

        //needed to make sure category filter filters for active category
        $params['parentCategoryIds'] = $params['category'] ?? null;
        $category = \Starfruit\ShopBundle\Model\AbstractCategory::getById($params['category'] ?? -1);
        $params['category'] = $category;

        // listing
        $productListing = $ecommerceFactory->getIndexService()->getProductListForCurrentTenant();
        if (isset($params['orderKey'])) {
            $productListing->setOrderKey($params['orderKey']);
        }
        if (isset($params['order'])) {
            $productListing->setOrder($params['order']);
        }
        $params['productListing'] = $productListing;

        // load current filter
        if ($category) {
            $filterDefinition = $category->getFilterdefinition();
        }
        if ($request->get('filterdefinition') instanceof \Pimcore\Model\DataObject\FilterDefinition) {
            $filterDefinition = $request->get('filterdefinition');
        }
        if (empty($filterDefinition)) {
            $filterDefinition = \Pimcore\Config::getWebsiteConfigValue('fallbackFilterdefinition');
        }
        if (empty($filterDefinition)) {
            return $params;
        }

        // filter
        $filterService = $ecommerceFactory->getFilterService();
        $listHelper->setupProductList($filterDefinition, $productListing, $params, $filterService, true);
        $params['filterService'] = $filterService;
        $params['filterDefinition'] = $filterDefinition;

        // pagination
        $limit = isset($params['perpage']) ? $params['perpage'] : ($filterDefinition->getPageLimit() ?: self::DEFAULT_LIMIT);
        $results = $paginator->paginate(
            $productListing,
            $request->get('page', 1),
            $limit
        );
        $params['results'] = $results;
        $params['paginationVariables'] = $results->getPaginationData();

        return $params;
    }
}
