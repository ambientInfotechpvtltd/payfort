var ambientPayfort = (function () {
   return {
        validateCardHolderName: function(element) {
            jQuery(element).val(this.trimString(element.val()));
            var cardHolderName = jQuery(element).val();
            if(cardHolderName.length > 50) {
                return false;
            }
            return true;
        },
        translate: function(key, category, replacments) {
            if(!this.isDefined(category)) {
                category = 'ambient_payfort';
            }
            var message = (arr_messages[category + '.' + key]) ? arr_messages[category + '.' + key] : key;
            if (this.isDefined(replacments)) {
                jQuery.each(replacments, function (obj, callback) {
                    message = message.replace(obj, callback);
                });
            }
            return message;
        },
        isDefined: function(variable) {
            if (typeof (variable) === 'undefined' || typeof (variable) === null) {
                return false;
            }
            return true;
        },
        isTouchDevice: function() {
            return 'ontouchstart' in window        // works on most browsers 
                || navigator.maxTouchPoints;       // works on IE10/11 and Surface
        },
        trimString: function(str){
            return str.trim();
        },
        isPosInteger: function(data) {
            var objRegExp  = /(^\d*$)/;
            return objRegExp.test( data );
        }
   };
})();

var ambientPayfortMerchantPage2 = (function () {
    var merchantPage2FormId = 'frm_ambient_payfort_payment';
    return {
        validateCcForm: function () {
            this.hideError();
            var isValid = ambientPayfort.validateCardHolderName($('#ambient_payfort_card_holder_name'));
            if(!isValid) {
                this.showError(ambientPayfort.translate('error_invalid_card_holder_name'));
                return false;
            }
            var expDate = jQuery('#ambient_payfort_expiry_year').val()+''+jQuery('#ambient_payfort_expiry_month').val();
            jQuery('#ambient_payfort_expiry').val(expDate);
            return true;
        },
        showError: function(msg) {
            jQuery('#ambient_payfort_msg').html(msg);
            jQuery('#ambient_payfort_msg').show();
        },
        hideError: function() {
            jQuery('#ambient_payfort_msg').hide();
        }
    };
})();

var ambientPayfortMerchantPage = (function () {
    return {
        showMerchantPage: function(gatewayUrl) {
            if(jQuery("#ambient_merchant_page").size()) {
                jQuery( "#ambient_merchant_page" ).remove();
            }
            jQuery("#review-buttons-container .btn-checkout").hide();
            jQuery("#review-please-wait").show();
            jQuery('<iframe  name="ambient_merchant_page" id="ambient_merchant_page"height="640px" frameborder="0" scrolling="no" onload="ambientPayfortMerchantPage.iframeLoaded(this)" style="display:none"></iframe>').appendTo('#pf_iframe_content');
            jQuery('.pf-iframe-spin').show();
            jQuery('.pf-iframe-close').hide();
            jQuery( "#ambient_merchant_page" ).attr("src", gatewayUrl);
            //jQuery( "#ambient_payment_form" ).attr("action",gatewayUrl);
            jQuery( "#ambient_payment_form" ).attr("target","ambient_merchant_page");
            jQuery( "#ambient_payment_form" ).submit();
            //fix for touch devices
            if (ambientPayfort.isTouchDevice()) {
                setTimeout(function() {
                    jQuery("html, body").animate({ scrollTop: 0 }, "slow");
                }, 1);
            }
            jQuery( "#div-pf-iframe" ).show();
        },
        closePopup: function() {
            jQuery( "#div-pf-iframe" ).hide();
            jQuery( "#ambient_merchant_page" ).remove();
            window.location = jQuery( "#ambient_cancel_url" ).val();
        },
        iframeLoaded: function(){
            jQuery('.pf-iframe-spin').hide();
            jQuery('.pf-iframe-close').show();
            jQuery('#ambient_merchant_page').show();
        },
    };
})();