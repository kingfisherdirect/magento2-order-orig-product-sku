<?php

namespace KingfisherDirect\OrderOrigProductSku\Plugin;

use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Model\Quote\Item\ToOrderItem;
use Magento\Sales\Api\Data\OrderItemInterface;

class CopyProductSkuToOrderItemPlugin
{
    /**
     * @var ExtensionAttributesFactory
     */
    private $extensionFactory;

    public function __construct(ExtensionAttributesFactory $extensionFactory)
    {
        $this->extensionFactory = $extensionFactory;
    }

    public function aroundConvert(ToOrderItem $subject, callable $next, $item, $data = [])
    {
        $result = $next($item, $data);

        if (!$item instanceof CartItemInterface || !$result instanceof OrderItemInterface) {
            return $result;
        }

        $extensionAttributes = $item->getExtensionAttributes();

        if (!$extensionAttributes) {
            return $result;
        }

        $productSku = $extensionAttributes->getProductSku();

        $resultExtensionAttributes = $result->getExtensionAttributes() ?: $this->extensionFactory->create(OrderItemInterface::class);
        $resultExtensionAttributes->setProductSku($productSku);
        $result->setExtenstionAttributes($resultExtensionAttributes);

        return $result;
    }
}
