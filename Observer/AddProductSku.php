<?php

namespace KingfisherDirect\OrderOrigProductSku\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class AddProductSku implements ObserverInterface
{
    public function execute(Observer $observer)
    {
        $quoteItem = $observer->getData('quote_item');

        $product = $quoteItem->getProduct();

        if (!$product) {
            return;
        }

        $quoteItemExtension = $quoteItem->getExtensionAttributes();
        $quoteItemExtension->setProductSku($product->getData('sku'));
        $quoteItem->setExtenstionAttributes($quoteItemExtension);
    }
}
