<?php

namespace Starfruit\ShopBundle\Controller;

use Starfruit\BuilderBundle\Tool\SystemTool;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use CustomerManagementFrameworkBundle\Security\Authentication\LoginManagerInterface;
use Starfruit\ShopBundle\EventListener\AuthenticationLoginListener;

class ShopAuthBaseController extends BaseController
{
    protected $request;
    protected $authenticationUtils;
    protected $loginManager;
    protected $authenticationLoginListener;

    public function __construct(
        AuthenticationUtils $authenticationUtils,
        LoginManagerInterface $loginManager,
        AuthenticationLoginListener $authenticationLoginListener,
    )
    {
        $this->request = SystemTool::getRequest();
        $this->authenticationUtils = $authenticationUtils;
        $this->loginManager = $loginManager;
        $this->authenticationLoginListener = $authenticationLoginListener;
    }

    protected function getAccountIndexRouteName()
    {
        return 'account-index';
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

    public function postRegister($customer)
    {
        $response = $this->redirectToRoute($this->getAccountIndexRouteName());

        // log user in manually
        // pass response to login manager as it adds potential remember me cookies
        $loginManager->login($customer, $this->request, $response);

        //do ecommerce framework login
        $this->authenticationLoginListener->doEcommerceFrameworkLogin($customer);

        return $response;
    }
}
