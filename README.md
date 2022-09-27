# Yireo CustomGraphQlQueryLimiter
Magento 2 module to customize settings for the GraphQL Query Limiter to enhance performance and security of your headless Magento.

### Installation
Install via composer (`composer require yireo/magento2-custom-graph-ql-query-limiter`), enable the module `Yireo_CustomGraphQlQueryLimiter`, refresh caching and do your magic with DI compilation, static content and database upgrades.

### Usage
The [Yireo CustomGraphQlQueryLimiter](CustomGraphQlQueryLimiter) module allows you to modify the query depth and the query complexity in an easy way, by simply letting you change these values from the **Magento Admin Panel**. Simply navigate to the **Store Configuration** in your backend and then to **Yireo > Yireo CustomGraphQlQueryLimiter > Settings** and set the desired values.
 
 Additionally, it also enables these settings in the Developer Mode, while Magento by default only enables this in Production Mode. It makes it easier to test things, instead of bumping into potential issues early.
 
 Once you update the settings, make sure to test this with an entire client-side app including complexer GraphQL queries, mutations and fragments (for instance, within Magento PWA Studio), because it potentially break your app.

### Introduction
Magento 2.3+ ships with a GraphQL API that allows you to make simple queries like these:
```graphql
{
  products(filter: {name: {match: "jacket"}}) {
    items { sku }
  }
}
``` 

Based upon the same kind of query, you can also create a recursive lookup of products and categories, which might be looking like this:
```graphql
{
  products(filter: {name: {match: "jacket"}}) {
    items {
      sku
      categories {
        products {
          items {
            sku
            categories {
              products {
                items {
                  sku
                  categories {
                    products {
                      items {
                        sku
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
  }
}
```
In my development environment, this quickly leads to timeouts, proofing that this type of query might not be benefiting a production environment, to put it lightly.

Magento 2 its GraphQL system is based on the [Webonyx GraphQL PHP library](https://github.com/webonyx/graphql-php/), which offers a couple of security mechanisms to prevent this kind of query from being handled: Query depth and query complexity. The Magento 2 core uses a DI type to set values for this, which could be overridden using your own DI type:

```xml
<type name="Magento\Framework\GraphQl\Query\QueryComplexityLimiter">
    <arguments>
        <argument name="queryDepth" xsi:type="number">20</argument>
        <argument name="queryComplexity" xsi:type="number">300</argument>
    </arguments>
</type>
```

Looking at the culprit query above, the depth is 10: Simply counting the opening curly braces `{` from the start of the return statement (`items`). By setting the `queryDepth` to 7 or 8, the query would generate an error instead:

```json
{
  "errors": [
    {
      "message": "Max query depth should be 7 but got 10.",
      "extensions": {
        "category": "graphql"
      }
    }
  ]
}
```

### Setting the complexity
The complexity could be modified as well. Looking at the culprit query above, the complexity is 15. It proves that the complexity of a GraphQL query might be quite low, but still the impact could be high. However, setting the complexity to 300 seems rather high. Perhaps in your case, setting it to 50 might be a better idea.

The Webonyx library also allows you to add a complexity function to a specific field definition. Theoretically, this is something that would need to be customized per query (`categories`, `products`). What makes a specific query less performant and would therefore need to be labeled as *complex*?  

Note that setting an empty value on either query depth or complexity in the database, will make the value to be skipped. At that moment, the original DI configuration is taken into account.
