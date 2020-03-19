<?php

namespace Ambient\Payfort\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\UrlInterface;
use Magento\Payment\Helper\Data as PaymentHelper;
use Ambient\Payfort\Helper\Data as pfHelper;

class PaymentConfigProvider implements ConfigProviderInterface
{
    /**
     * @var string[]
     */
    protected $methodCodes = [
        \Ambient\Payfort\Model\Method\Cc::CODE,
        \Ambient\Payfort\Model\Method\Sadad::CODE,
        \Ambient\Payfort\Model\Method\Naps::CODE,
        \Ambient\Payfort\Model\Method\Installments::CODE,
    ];

    /**
     * @var \Magento\Payment\Model\Method\AbstractMethod[]
     */
    protected $methods = [];

    /**
     * @var pfHelper
     */
    protected $pfHelper;

    /**
     * @var PaymentHelper
     */
    protected $paymentHelper;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @param PaymentHelper $paymentHelper
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        PaymentHelper $paymentHelper,
        pfHelper $pfHelper,
        UrlInterface $urlBuilder
    ) {
        $this->paymentHelper = $paymentHelper;
        $this->pfHelper = $pfHelper;
        $this->urlBuilder = $urlBuilder;

        foreach ($this->methodCodes as $code) {
            $this->methods[$code] = $this->paymentHelper->getMethodInstance($code);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $config = [
            'payment' => [
                'ambientPayfort' => [],
            ],
        ];
        $config['payment']['ambientPayfort']['configParams']['gatewayCurrency'] = $this->pfHelper->getGatewayCurrency();
        $ccIntegrationType = $this->methods[\Ambient\Payfort\Model\Method\Cc::CODE]->getConfigData('integration_type');
        $config['payment']['ambientPayfort'][\Ambient\Payfort\Model\Method\Cc::CODE]['integrationType'] = $ccIntegrationType;
        foreach ($this->methodCodes as $code) {
            if ($this->methods[$code]->isAvailable()) {
                $config['payment']['ambientPayfort'][$code]['redirectUrl']  = $this->getActionUrl($code);
                $config['payment']['ambientPayfort'][$code]['instructions'] = $this->methods[$code]->getInstructions();
                if($code == \Ambient\Payfort\Model\Method\Cc::CODE || $code == \Ambient\Payfort\Model\Method\Installments::CODE) {
                    $config['payment']['ambientPayfort'][$code]['isMerchantPage'] = $this->methods[$code]->isMerchantPage();
                    $config['payment']['ambientPayfort'][$code]['merchantPageUrl'] = $this->urlBuilder->getUrl('ambientpayfort/payment/merchantPage', ['_secure' => true]);
                    if($ccIntegrationType == \Ambient\Payfort\Helper\Data::AMBIENT_PAYFORT_INTEGRATION_TYPE_MERCAHNT_PAGE2) {
                        $config['payment']['ambientPayfort'][$code]['ajaxUrl']  = $this->pfHelper->getReturnUrl('ambientpayfort/payment/getPaymentData');
                        $config['payment']['ccform']['availableTypes'][$code] = $this->methods[$code]->getCcAvailableTypes();
                        $config['payment']['ccform']['years'][$code] = $this->methods[$code]->getCcYears();
                        $config['payment']['ccform']['months'][$code] = $this->methods[$code]->getCcMonths();
                        $config['payment']['ccform']['hasVerification'][$code] = $this->methods[$code]->hasVerification();
                        $config['payment']['ccform']['ssStartYears'][$code] = $this->methods[$code]->getSsStartYears();
                        $config['payment']['ccform']['hasSsCardType'][$code] = false;
                        $config['payment']['ccform']['cvvImageUrl'][$code] = $this->methods[$code]->getCvvImageUrl();
                    }
                }
            }
        }
        return $config;
    }

    /**
     * Get frame action URL
     *
     * @param string $code
     * @return string
     */
    protected function getActionUrl($code)
    {
        $url = $this->urlBuilder->getUrl('ambientpayfort/payment/redirect', ['_secure' => true]);

        return $url;
    }
}
