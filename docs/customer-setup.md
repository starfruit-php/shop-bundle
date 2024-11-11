# Step by step to setup customer flow

## Classes

### Customer

- Create `Customer` class
- Import `Customer` example class from `vendor/pimcore/customer-management-framework-bundle/install/lass_source/optional/class_Customer_export.json`
- Set `Parent PHP Class` in `General Setting` = `Starfruit\ShopBundle\Model\AbstractCustomer`

## Config

- Import `cmf.yaml` config to `config.yaml`:

```bash
    imports:
        ...
        - { resource: './../vendor/starfruit/shop-bundle/config/pimcore/cmf.yaml', ignore_errors: true }
```

## Security firewall

```bash
security:
    enable_authenticator_manager: true

    providers:
        cmf_customer_provider:
            id: cmf.security.user_provider

    ...

    firewalls:
        # Pimcore CMF bundle firewall
        cmf_webservice: '%customer_management_framework.firewall_settings%'

        # demo_frontend firewall is valid for the whole site
        demo_frontend:
            provider: cmf_customer_provider
            entry_point: form_login

            form_login:
                enable_csrf: true
                login_path: 'auth-login'
                check_path: 'auth-login'
                default_target_path: 'account-index'


            logout:
                path: 'account-logout'
                target: /

                # optional: to keep logins of other firewalls (like admin)
                invalidate_session: false

            remember_me:
                secret: '%kernel.secret%'
```

## Setup auth flow

### Login

#### Controller

#### Template
