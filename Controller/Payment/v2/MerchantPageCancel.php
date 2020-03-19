<?php

namespace Ambient\Payfort\Controller\Payment;

class MerchantPageCancel extends \Ambient\Payfort\Controller\Checkout
{
    public function execute()
    {
        $this->_cancelCurrenctOrderPayment('User has cancel the payment');
        $this->_checkoutSession->restoreQuote();
        $message = __('You have canceled the payment.');
        $this->messageManager->addError( $message );
        $returnUrl = $this->getHelper()->getUrl('checkout/cart');
        $this->getResponse()->setRedirect($returnUrl);
    }
}

?>
