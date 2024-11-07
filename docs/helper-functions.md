# Helper functions

## Product

### Get list in template

```bash
    {% set items = shop_product_listing() %}
```

### Using ecommerce filter

```bash
    {% set filterDefinitionItems = items.filterDefinition.filters.items %}
    {% for filter in filterDefinitionItems %}
        {% set filterMarkup = items.filterService.filterFrontend(filter, items.productListing, items.currentFilter) %}

        {{ filterMarkup|raw  }}
    {% endfor %}
```