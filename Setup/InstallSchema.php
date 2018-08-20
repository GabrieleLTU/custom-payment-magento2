<?php

namespace Visma\CustomPayment\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{

    public function install(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->tableExists('visma_custompayment')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('visma_custompayment')
            )
                ->addColumn(
                    'custompayment_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    [
                        'auto_increment' => true,
                        'identity' => true,
                        'nullable' => false,
                        'primary'  => true,
                        'unsigned' => true,
                    ],
                    'Custom payment ID'
                )
                ->addColumn(
                    'postback_uri',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    [
                        'default' => ''
                    ],
                    'Postback URI'
                )
                ->addColumn(
                    'klarna_order_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    [
                        'nullable' => false,
                        'default' => ''
                    ],
                    'Order ID from Klarna'
                )
                ->addColumn(
                    'status_uri',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    [
                        'default' => ''
                    ],
                    'Status URI'
                )
                ->addColumn(
                    'order_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    [
                        'nullable' => false,
                    ],
                    'Order ID'
                )
                ->addColumn(
                    'orderincrement_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    [
                        'nullable' => false,
                    ],
                    'Order Increment ID'
                )
//                ->addColumn(
//                    'create_order_timestamp',
//                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
//                    null,
//                    [
//                        'nullable' => false,
//                    ],
//                    'Create Order Timestamp'
//                )
                ->setComment('Table for custom payment with Klarna');
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}