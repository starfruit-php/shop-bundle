<?php

namespace Starfruit\ShopBundle\Controller;

use Starfruit\BuilderBundle\Tool\SystemTool;
use Pimcore\Bundle\EcommerceFrameworkBundle\Factory;
use Pimcore\Bundle\EcommerceFrameworkBundle\FilterService\ListHelper;
use Pimcore\Translation\Translator;
use Knp\Component\Pager\PaginatorInterface;

use Starfruit\ShopBundle\Service\ProductService;

class ShopProductBaseController extends BaseController
{
    protected $request;
    protected $ecommerceFactory;
    protected $listHelper;
    protected $paginator;
    protected $translator;

    public function __construct(
        Factory $ecommerceFactory,
        ListHelper $listHelper,
        PaginatorInterface $paginator,
        Translator $translator,
        )
    {
        $this->request = SystemTool::getRequest();
        $this->ecommerceFactory = $ecommerceFactory;
        $this->listHelper = $listHelper;
        $this->paginator = $paginator;
        $this->translator = $translator;
    }

    public function getList()
    {
        $params = ProductService::getList($this->request, $this->ecommerceFactory, $this->listHelper, $this->paginator);

        return $params;
    }
}
