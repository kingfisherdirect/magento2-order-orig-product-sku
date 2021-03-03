# Kingfisher Direct - Order Orig Product SKU

This module adds product_sku property to order items (Quote item and Sales Order Item).

By default magento saves product sku with all options.

This is a module with similar functionality to https://github.com/magento/magento2/pull/30667 , which as of today seems to be targeted for Magento 2.5

## Installation

`composer require kingfisherdirect/magento2-order-orig-product-sku`

## Usage

The original product SKU can be accessed through API. The value will be within
extension_attributes, under product_sku key.

```
{
    ...
    ...
    "created_at": "2021-03-03 10:54:26",
    "item_id": 24817,
    "name": "Some Product Name",
    "order_id": 2282,
    "original_price": 0,
    "parent_item_id": 4816,
    "price": 621.42,
    "product_id": 572,
    "product_type": "simple",
    "quote_item_id": 45011,
    "sku": "PRODUCTSKU-WITH-OPTION",
    "extension_attributes": {
        "product_sku": "PRODUCTSKU"
    }
    ...
}
```

## Tests

At the moment this software was manually tested on Magento 2.3.5.
