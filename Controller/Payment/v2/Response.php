<?php

namespace Ambient\Payfort\Controller\Payment;

class Response extends \Ambient\Payfort\Controller\Checkout
{
    
    public function execute()
    {
        
        $orderId            = $this->getRequest()->getParam('merchant_reference');
        $order              = $this->getOrderById($orderId);
        $responseParams     = $this->getRequest()->getParams();          
        $helper = $this->getHelper();  
        
        $integrationType    = $helper::AMBIENT_PAYFORT_INTEGRATION_TYPE_REDIRECTION;
        $paymentMethod      = $order->getPayment()->getMethod();

        if($paymentMethod == $helper::AMBIENT_PAYFORT_PAYMENT_METHOD_CC) {
            $integrationType = $helper->getConfig('payment/ambient_payfort_cc/integration_type');
        }
        elseif($paymentMethod == $helper::AMBIENT_PAYFORT_PAYMENT_METHOD_INSTALLMENTS) {
            $integrationType = $helper->getConfig('payment/ambient_payfort_installments/integration_type');
        }
        
        $success = $helper->handlePayfortResponse($responseParams, 'offline', $integrationType);
        if ($success) {
            $returnUrl = $helper->getUrl('checkout/onepage/success');
        }
        else {
            $returnUrl = $this->getHelper()->getUrl('checkout/cart');
        }
        $this->orderRedirect($order, $returnUrl);
    }

}