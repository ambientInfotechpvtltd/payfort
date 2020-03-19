<?php

namespace Ambient\Payfort\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\TestFramework\Event\Magento;
use Ambient\Payfort\Model\Payment as AmbientPayment;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install( ModuleDataSetupInterface $setup, ModuleContextInterface $context )
    {
        $installer = $setup;

        $installer->startSetup();

        /**
         * Install order statuses from config
         */
        $data = [];
        $statuses = [
            AmbientPayment::STATUS_NEW => __('Ambient New Order'),
            AmbientPayment::STATUS_FAILED => __('Ambient Failed'),
        ];

        foreach ($statuses as $code => $info) {
            $data[] = ['status' => $code, 'label' => $info];
        }

        $installer->getConnection()->insertArray(
            $installer->getTable('sales_order_status'),
            ['status', 'label'],
            $data
        );


        /**
         * Install order states from config
         */
        $data = [];
        $states = [
            'new' => [
                'statuses' => [ AmbientPayment::STATUS_NEW ],
            ],
            'canceled' => [
                'statuses' => [ AmbientPayment::STATUS_FAILED ],
            ],
        ];

        foreach ($states as $code => $info) {
            if (isset($info['statuses'])) {
                foreach ($info['statuses'] as $status) {
                    $data[] = [
                        'status' => $status,
                        'state' => $code,
                        'is_default' => 0,
                        'visible_on_front' => 1,
                    ];
                }
            }
        }
        $installer->getConnection()->insertArray(
            $installer->getTable('sales_order_status_state'),
            ['status', 'state', 'is_default', 'visible_on_front'],
            $data
        );

        $installer->endSetup();
    }
}
