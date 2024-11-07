# Step by step to setup ecommerce

## Classes

### Category

- Create `Category` class
- Set `Parent PHP Class` in `General Setting` = `Starfruit\ShopBundle\Model\AbstractCategory`
- Create `filterDefinition` field with type `Many-To-One Relation` and mapping to `FilterDefinition` class (E-Commerce class)

### Product

- Create `Product` class
- Set `Parent PHP Class` in `General Setting` = `Starfruit\ShopBundle\Model\AbstractProduct`
- Create `categories` field with type `Many-To-Many Object Relation` and mapping to `Category` class

### FilterDefinition

- Create an item in `Website Settings` with name `fallbackFilterdefinition` and value is default `FilterDefinition` object
- `FilterDefinition` objects with `FilterCategory` must have the `Include SubCategories` field checked
- Create templates for filter config or overwritten it, [see config here](./../config/pimcore/ecommerce-filter.yaml)
- Example code to render filter in template [here](helper-functions.md#using-ecommerce-filter)

## Index config

- Create `ecommerce.yaml` file in `config`, example content:

```bash
    pimcore_ecommerce_framework:
        index_service:
            tenants:
                default:
                    config_id: Starfruit\ShopBundle\Ecommerce\IndexService\Config\MySqlConfig
                    worker_id: Pimcore\Bundle\EcommerceFrameworkBundle\IndexService\Worker\DefaultMysql
                    attributes:
                        name:
                            type: varchar(255)
                            filter_group: string
                        price:
                            type: double
                            filter_group: double
                        code:
                            type: varchar(255)
                            filter_group: string
                        brand:
                            interpreter_id: Pimcore\Bundle\EcommerceFrameworkBundle\IndexService\Interpreter\DefaultObjects
                            filter_group: relation
                        parentCate:
                            type: integer
                        isPriority:
                            type: boolean
```

- Import it to `config.yaml`:

```bash
    imports:
        ...
        - { resource: 'ecommerce.yaml' }
```

## Create ecommerce tables in database

- Pimcore document [here](https://pimcore.com/docs/platform/Ecommerce_Framework/Index_Service/Product_Index_Configuration/Data_Architecture_and_Indexing_Process#console-commands-for-simple-mysql-architecture)

- Create or update the index structures: 
```bash
    php bin/console ecommerce:indexservice:bootstrap --create-or-update-index-structure
```

- Updating the whole index: 
```bash
    php bin/console ecommerce:indexservice:bootstrap --update-index
```