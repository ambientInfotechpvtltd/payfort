/**
 * Payfot_Payfort Magento JS component
 *
 * @category    Ambient
 * @package     Payfot_Payfort
 */
/*browser:true*/
/*global define*/
define(
    [
        'jquery',
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list',
        'Magento_Checkout/js/action/select-payment-method',
        'Magento_Checkout/js/checkout-data',
    ],
    function (
        $,
        Component,
        rendererList,
        selectPaymentMethodAction,
        checkoutData
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'ambient_payfort_cc',
                component: window.checkoutConfig.payment.ambientPayfort.ambient_payfort_cc.integrationType == 'merchantPage2' ? 'Ambient_Payfort/js/view/payment/method-renderer/ambient_payfort_cc_merchant_page2-method' : 'Ambient_Payfort/js/view/payment/method-renderer/ambient_payfort_cc-method'
            },
            {
                type: 'ambient_payfort_installments',
                component: 'Ambient_Payfort/js/view/payment/method-renderer/ambient_payfort_installments-method'
            },
            {
                type: 'ambient_payfort_sadad',
                component: 'Ambient_Payfort/js/view/payment/method-renderer/ambient_payfort_sadad-method'
            },
            {
                type: 'ambient_payfort_naps',
                component: 'Ambient_Payfort/js/view/payment/method-renderer/ambient_payfort_naps-method'
            }
        );
        /** Add view logic here if needed */
        return Component.extend({
            initialize : function() {
                this._super();
                if(window.checkoutConfig.payment.ambientPayfort.configParams.gatewayCurrency == 'front') {
                    $(document).on('change', 'input[name="payment[method]"]', function (){
                        if($(this).val() == 'ambient_payfort_cc' || $(this).val() == 'ambient_payfort_sadad' || $(this).val() == 'ambient_payfort_naps' || $(this).val() == 'ambient_payfort_installments' ) {
                            $('.totals.charge').hide();
                        }
                        else {
                            $('.totals.charge').show();
                        }
                    });
                }
                return true;
            },
        });
    }
);