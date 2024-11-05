Starfruit Shop Bundle
<!-- [TOC] -->

# Requirement

## [E-Commerce Framework](https://pimcore.com/docs/platform/Ecommerce_Framework/ "E-Commerce Framework")

### Install bundle

```bash
    composer require pimcore/ecommerce-framework-bundle
```

### Update `config/bundle.php`

```bash
    return [
        ...
        Pimcore\Bundle\EcommerceFrameworkBundle\PimcoreEcommerceFrameworkBundle::class => ['all' => true],
```

### Setup

```bash
    php bin/console pimcore:bundle:install PimcoreEcommerceFrameworkBundle
```
## [Customer Management Framework](https://pimcore.com/docs/platform/Customer_Management_Framework/Installation/ "Customer Management Framework")

### Install bundle

```bash
    composer require pimcore/customer-management-framework-bundle
```

### Update `config/bundle.php`

```bash
    return [
        ...
        \CustomerManagementFrameworkBundle\PimcoreCustomerManagementFrameworkBundle::class => ['all' => true],
        Pimcore\Bundle\ObjectMergerBundle\ObjectMergerBundle::class => ['all' => true],
```

### Setup

```bash
    php bin/console pimcore:bundle:install PimcoreCustomerManagementFrameworkBundle
```

# Installation

- On your Pimcore 11 root project:

```bash
    composer require starfruit/shop-bundle
```

- Update `config/bundles.php` file:

```bash
    return [
        ....
        Starfruit\ShopBundle\StarfruitShopBundle::class => ['all' => true],
    ];
```
