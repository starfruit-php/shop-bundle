<?php

namespace Starfruit\ShopBundle\Model;

use Pimcore\Model\DataObject\Data\Hotspotimage;

abstract class AbstractProduct extends \Pimcore\Bundle\EcommerceFrameworkBundle\Model\AbstractProduct
{
    /**
     * @return Hotspotimage|null
     */
    public function getMainImage(): ?Hotspotimage
    {
        return null;
    }

    public function isActive(bool $inProductList = false): bool
    {
        return $this->isPublished();
    }

    public function getPriceSystemName(): string
    {
        return 'default';
    }

    /**
     * @return string|null
     */
    public function getPrice()
    {
        $saleInformationBrick = $this->getSaleInformation();
        $saleInformation = $saleInformationBrick->getSaleInformation();
        if ($saleInformation) {
            return $saleInformation->getPriceInEUR();
        }

        return null;
    }
}
