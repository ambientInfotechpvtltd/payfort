<?php

namespace Ambient\Payfort\Controller;

use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;

/**
 * Ambient Checkout Controller
 */
abstract class Checkout extends \Magento\Framework\App\Action\Action implements CsrfAwareActionInterface
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $_orderFactory;

    /**
     * @var \Ambient\Payfort\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magento\Quote\Model\Quote
     */

    /**
     *
     * @var \Ambient\Payfort\Model\Payment
     */
    protected $_ambientModel;

    /**
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_pageFactory;


    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param \Ambient\Payfort\Model\Payment $ambientModel
     * @param \Ambient\Payfort\Helper\Checkout $checkoutHelper
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Ambient\Payfort\Model\Payment $ambientModel,
        \Ambient\Payfort\Helper\Data $helper
    ) {
        $this->_pageFactory = $customerSession;
        $this->_customerSession = $customerSession;
        $this->_checkoutSession = $checkoutSession;
        $this->_orderFactory = $orderFactory;
        $this->_ambientModel = $ambientModel;
        $this->_helper = $helper;
        parent::__construct($context);
    }

    public function createCsrfValidationException(
        RequestInterface $request
    ): ?InvalidRequestException {
        return null;
    }

    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }

    /**
     * Cancel order, return quote to customer
     *
     * @param string $errorMsg
     * @return false|string
     */
    protected function _cancelCurrenctOrderPayment($errorMsg = '')
    {
        $gotoSection = false;
        $this->_helper->cancelCurrentOrder($errorMsg);
        if ($this->_checkoutSession->restoreQuote()) {
            //Redirect to payment step
            $gotoSection = 'paymentMethod';
        }

        return $gotoSection;
    }

    /**
     * Cancel order, return quote to customer
     *
     * @param string $errorMsg
     * @return false|string
     */
    protected function _cancelPayment($order, $errorMsg = '')
    {
        return $this->_helper->cancelOrder($order, $errorMsg);
    }

    /**
     * Get order object
     *
     * @return \Magento\Sales\Model\Order
     */
    protected function getOrderById($order_id)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $order = $objectManager->get('Magento\Sales\Model\Order');
        $order_info = $order->loadByIncrementId($order_id);
        return $order_info;
    }

    /**
     * Get order object
     *
     * @return \Magento\Sales\Model\Order
     */
    protected function getOrder()
    {
        return $this->_orderFactory->create()->loadByIncrementId(
            $this->_checkoutSession->getLastRealOrderId()
        );
    }

    protected function getQuote()
    {
        if (!$this->_quote) {
            $this->_quote = $this->_getCheckoutSession()->getQuote();
        }
        return $this->_quote;
    }

    protected function getCheckoutSession()
    {
        return $this->_checkoutSession;
    }

    protected function getCustomerSession()
    {
        return $this->_customerSession;
    }

    protected function getAmbientModel()
    {
        return $this->_ambientModel;
    }

    protected function getHelper()
    {
        return $this->_helper;
    }

    public function orderRedirect($order, $returnUrl) {
        if($this->_helper->isMerchantPageMethod($order)) {
            echo "<html><body onLoad=\"javascript: window.top.location.href='" . $this->_helper->getUrl($returnUrl) . "'\"></body></html>";
            exit;
        }
        else{
            $this->getResponse()->setRedirect($returnUrl);
        }
    }
}
