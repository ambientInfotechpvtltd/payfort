<?php

namespace Ambient\Payfort\Controller\Payment;

class ResponseOnline extends \Ambient\Payfort\Controller\Checkout
{
    
    public function execute()
    {
        $orderId = $this->getRequest()->getParam('merchant_reference');
        $order = $this->getOrderById($orderId);
        $responseParams = $this->getRequest()->getParams();
        $helper = $this->getHelper();
        $integrationType = $helper::AMBIENT_PAYFORT_INTEGRATION_TYPE_REDIRECTION;
        $success = $helper->handlePayfortResponse($responseParams, 'online', $integrationType);
        if ($success) {
            $returnUrl = $helper->getUrl('checkout/onepage/success');
        }
        else {
            $returnUrl = $this->getHelper()->getUrl('checkout/cart');
        }
        $this->orderRedirect($order, $returnUrl);
    }

}
