<?php

namespace Ambient\Payfort\Block\Adminhtml\System\Config\Field;

class hostToHostUrl extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * Helper
     *
     * @var \Ambient\Payfort\Helper\Data
     */
    protected $_helper;

    /**
     * Path to template file in theme.
     *
     * @var string
     */
    protected $_template = 'host_to_host_url.phtml';

    private $_base_currency = '';

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Ambient\Payfort\Helper\Data $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Ambient\Payfort\Helper\Data $helper,
        array $data = []
    )
    {
        $this->_helper = $helper;
        parent::__construct( $context, $data );
    }
    
    /**
     * Retrieve HTML markup for given form element
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $element->setRenderer( $this );
        return $this->_toHtml();
    }
    
    public function getHostUrl() {
        return $this->_helper->getReturnUrl('ambientpayfort/payment/response');
    }
}
