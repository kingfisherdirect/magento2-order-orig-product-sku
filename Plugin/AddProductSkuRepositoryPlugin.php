<?php

namespace KingfisherDirect\OrderOrigProductSku\Plugin;

use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Quote\Api\CartItemRepositoryInterface;
use Magento\Quote\Api\Data\CartItemExtensionFactory;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Sales\Api\Data\OrderItemInterface;

class AddProductSkuRepositoryPlugin
{
    const PROP_PRODUCT_SKU = 'product_sku';

    // /**
    //  * @var CartItemExtensionFactory
    //  */
    // private $extensionFactory;

    // public function __construct(ExtensionAttributesFactory $extensionFactory)
    // {
    //     $this->extensionFactory = $extensionFactory;
    // }

    public function afterGet($subject, object $item)
    {
        return $this->setupProductSkuExtensionAttribute($item);
    }

    public function afterGetList($subject, $output)
    {
        $items = $output instanceof SearchResultsInterface ? $output->getItems() : $output;

        foreach ($items as $item) {
            $this->setupProductSkuExtensionAttribute($item);
        }

        return $output;
    }

    public function aroundSave($subject, callable $next, $item)
    {
        if (!($item instanceof CartItemInterface) && !($item instanceof OrderItemInterface)) {
            throw new \Exception(sprintf(
                "This method expects either '%s' or '%s' classes, got '%s'",
                CartItemInterface::class,
                OrderItemInterface::class,
                get_class($item)
            ));
        }

        $extensionAttributes = $item->getExtensionAttributes();

        if (!$extensionAttributes) {
            return $next($item);
        }

        $origProductSku = $item->getData(self::PROP_PRODUCT_SKU);
        $item->setData(self::PROP_PRODUCT_SKU, $extensionAttributes->getProductSku());

        $returnValue = $next($item);

        $item->setData(self::PROP_PRODUCT_SKU, $origProductSku);

        return $returnValue;
    }

    private function setupProductSkuExtensionAttribute(object $item)
    {
        if (!($item instanceof CartItemInterface) && !($item instanceof OrderItemInterface)) {
            throw new \Exception(sprintf(
                "This method expects either '%s' or '%s' classes, got '%s'",
                CartItemInterface::class,
                OrderItemInterface::class,
                get_class($item)
            ));
        }

        $productSku = $item->getData(self::PROP_PRODUCT_SKU);
        $item->setData(self::PROP_PRODUCT_SKU, null);

        $extensionAttributes = $item->getExtensionAttributes();
        $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create(get_class($item));
        $extensionAttributes->setProductSku($productSku);
        $item->setExtenstionAttributes($extensionAttributes);

        return $item;
    }
}
