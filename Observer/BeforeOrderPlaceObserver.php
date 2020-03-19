<?php
namespace Ambient\Payfort\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Sales order place  observer
 */
class BeforeOrderPlaceObserver implements ObserverInterface 
{
    /**
     * @var \Magento\Config\Model\ResourceModel\Config
     */
    protected $_helper;
    
    /**
     * @param \Magento\CatalogInventory\Model\ResourceModel\Stock $resourceStock
     */
    public function __construct(
            \Ambient\Payfort\Helper\Data $helper  
            )
    {
        $this->_helper = $helper;
    }
    
    /**
     * Update items stock status and low stock date.
     *
     * @param EventObserver $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getOrder();
        $paymentMethod = $order->getPayment()->getMethod();
        if ($this->_helper->isAmbientPaymentMethod($paymentMethod)) {
            $order->setCanSendNewEmailFlag(false);
        }
        
    }
}
