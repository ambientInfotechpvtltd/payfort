<?php
/**
 * Payment Command Types Source Model
 *
 * @category    Ambient
 * @package     Ambient_Payfort
 */

namespace Ambient\Payfort\Model\Config\Source;

class Commandoptions implements \Magento\Framework\Option\ArrayInterface
{
    const AUTHORIZATION = 'AUTHORIZATION';
    const PURCHASE      = 'PURCHASE';
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::AUTHORIZATION,
                'label' => __('AUTHORIZATION')
            ],
            [
                'value' => self::PURCHASE,
                'label' => __('PURCHASE')
            ]
        ];
    }
}
