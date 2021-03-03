<?php

namespace KingfisherDirect\OrderOrigProductSku\Plugin;

use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Quote\Api\CartItemRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\Data\CartItemExtensionFactory;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Sales\Api\Data\OrderItemInterface;

class SaveProductSkuQuoteSaveHandlerPlugin
{
    public function aroundSave($subject, callable $next, CartInterface $quote)
    {
        $items = $quote->getAllItems();
        $origSkus = [];

        foreach ($items as $key => $item) {
            $extensionAttributes = $item->getExtensionAttributes();

            if (!$extensionAttributes || $item->isDeleted()) {
                continue;
            }

            $origSkus[$key] = $item->getData(AddProductSkuRepositoryPlugin::PROP_PRODUCT_SKU);
            $item->setData(AddProductSkuRepositoryPlugin::PROP_PRODUCT_SKU, $extensionAttributes->getProductSku());
        }

        $returnValue = $next($quote);

        // revert original product_sku values
        foreach ($items as $key => $item) {
            if (!\array_key_exists($key, $origSkus)) {
                continue;
            }

            $item->setData(AddProductSkuRepositoryPlugin::PROP_PRODUCT_SKU, $origSkus[$key]);
        }

        return $returnValue;
    }
}
