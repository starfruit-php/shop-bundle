<?php

namespace Starfruit\ShopBundle\Controller;

use Starfruit\BuilderBundle\Tool\SystemTool;
use Pimcore\Translation\Translator;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ShopAuthBaseController extends BaseController
{
    protected $authenticationUtils;
    protected $request;

    public function __construct(
        AuthenticationUtils $authenticationUtils,
    )
    {
        $this->request = SystemTool::getRequest();
        $this->authenticationUtils = $authenticationUtils;
    }

    public function login()
    {
        // if logged in
        if ($this->getUser()) {
            return [
                'user' => $this->user,
            ];
        }

        // get the login error if there is one
        $error = $this->authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $this->authenticationUtils->getLastUsername();

        return [
            '_username' => $lastUsername,
            'error' => $error,
        ];
    }
}
