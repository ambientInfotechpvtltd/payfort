<?php
/**
 * Payment Command Types Source Model
 *
 * @category    Ambient
 * @package     Ambient_Payfort
 */

namespace Ambient\Payfort\Model\Method;

class Naps extends \Ambient\Payfort\Model\Payment
{
    const CODE = 'ambient_payfort_naps';

    protected $_code = self::CODE;
    protected $_helper;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Ambient\Payfort\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Method\Logger $logger,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $paymentData,
            $scopeConfig,
            $logger,
            $resource,
            $resourceCollection,
            $data
        );

        $this->_code = self::CODE;
        $this->_helper = $paymentData;
    }

    /**
     * Get instructions text from config
     *
     * @return string
     */
    public function getInstructions()
    {
        return parent::getInstructions();
    }

    /**
     * Determine method availability based on quote amount and config data
     *
     * @param \Magento\Quote\Api\Data\CartInterface|null $quote
     * @return bool
     */
    public function isAvailable(\Magento\Quote\Api\Data\CartInterface $quote = null)
    {
        if ($quote && (
            $quote->getBaseGrandTotal() < $this->_minAmount
            || ($this->_maxAmount && $quote->getBaseGrandTotal() > $this->_maxAmount))
        ) {
            return false;
        }

        $baseCurrency                    = $this->_helper->getBaseCurrency();
        $frontCurrency                   = $this->_helper->getFrontCurrency();
        $currency                        = $this->_helper->getPayfortCurrency($baseCurrency, $frontCurrency);
        if ($currency != 'QAR') {
            return false;
        }

        return parent::isAvailable($quote);
    }
}
