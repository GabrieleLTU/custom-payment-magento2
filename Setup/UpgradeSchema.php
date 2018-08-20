<?php
namespace Visma\CustomPayment\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;


class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;
//        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/cron.log');
//        $logger = new \Zend\Log\Logger();
//        $logger->addWriter($writer);
//        $logger->info(__METHOD__);

        $installer->startSetup();
//        if (version_compare($context->getVersion(), "1.0.0", "<")) {
//            //Your upgrade script
//        }
        if (version_compare($context->getVersion(), '1.0.2', '<')) {

            $installer->getConnection()->addColumn(
                $installer->getTable('visma_custompayment'),
                'create_order_timestamp',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    'length' => null,
                    'nullable' => false,
                    'comment' => 'Create Order Timestamp'
                ]
            );
        }
        $installer->endSetup();
    }
}