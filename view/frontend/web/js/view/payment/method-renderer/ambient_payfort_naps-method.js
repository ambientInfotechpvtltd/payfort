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
        'ko',
        'jquery',
        'Magento_Checkout/js/view/payment/default',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/full-screen-loader',
        'Magento_Checkout/js/action/set-payment-information',
        'Magento_Checkout/js/action/place-order',
    ],
    function (ko, $, Component, quote, fullScreenLoader, setPaymentInformationAction, placeOrder) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'Ambient_Payfort/payment/ambient-form'
            },
            getCode: function() {
                return 'ambient_payfort_naps';
            },
            isActive: function() {
                return true;
            },
            context: function() {
                return this;
            },
            getInstructions: function() {
                return window.checkoutConfig.payment.ambientPayfort.ambient_payfort_naps.instructions;
            },
            // Overwrite properties / functions
            redirectAfterPlaceOrder: false,
            
            afterPlaceOrder : function() {
                $.mage.redirect(window.checkoutConfig.payment.ambientPayfort.ambient_payfort_naps.redirectUrl);
            },
            isChecked: ko.computed(function () {
                var checked = quote.paymentMethod() ? quote.paymentMethod().method : null;
                if(window.checkoutConfig.payment.ambientPayfort.configParams.gatewayCurrency == 'front') {
                    if(checked) {
                        $('.totals.charge').hide();
                    }
                }
                return checked;
            }),
        });
    }
);
