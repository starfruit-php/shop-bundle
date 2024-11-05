<?php

namespace Starfruit\ShopBundle\Ecommerce\IndexService\Config;

use Starfruit\ShopBundle\Model\AbstractProduct;
use Pimcore\Bundle\EcommerceFrameworkBundle\IndexService\Config\DefaultMysql;
use Pimcore\Bundle\EcommerceFrameworkBundle\Model\IndexableInterface;

class MySqlConfig extends DefaultMysql
{
    /**
     * @return string
     */
    public function getTablename(): string
    {
        return 'shopbundle_ecommerce_products';
    }

    /**
     * @return string
     */
    public function getRelationTablename(): string
    {
        return 'shopbundle_ecommerce_relations';
    }

    /**
     * @param IndexableInterface $object
     * @return bool
     */
    public function inIndex(IndexableInterface $object): bool
    {
        return $object instanceof AbstractProduct;
    }
}
