<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * Installer
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

/**
 * Create table 'easyshippingrules_carrier'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('easyshippingrules/carrier'))
    ->addColumn('easyshippingrules_carrier_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary'  => true,
    ), 'Carrier ID')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 50, array(
        'nullable' => true,
        'default'  => null,
    ), 'Name')
    ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_SMALLINT, 6, array(
        'nullable' => true,
        'default'  => 0,
    ), 'Is Active')
    ->addColumn('position', Varien_Db_Ddl_Table::TYPE_SMALLINT, 6, array(
        'nullable' => true,
        'default'  => 0,
    ), 'Position')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => true,
    ), 'Created At')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => true,
    ), 'Updated At')
    ->setComment('Easy Shipping Rules - Carrier');

$installer
    ->getConnection()
    ->createTable($table);

/**
 * Create table 'easyshippingrules_custom_method'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('easyshippingrules/custom_method'))
    ->addColumn('easyshippingrules_custom_method_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary'  => true,
    ), 'Custom Method ID')
    ->addColumn('easyshippingrules_carrier_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => true,
        'default'  => null,
    ), 'Custom Method ID')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 50, array(
        'nullable' => false,
        'default'  => null,
    ), 'Name')
    ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_SMALLINT, 6, array(
        'nullable' => true,
        'default'  => 0,
    ), 'Is Active')
    ->addColumn('price_action', Varien_Db_Ddl_Table::TYPE_VARCHAR, 50, array(
        'nullable' => true,
        'default'  => null,
    ), 'Price Action')
    ->addColumn('price_percentage', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,2', array(
        'nullable' => true,
        'default'  => null,
    ), 'Price or Percentage')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => true,
    ), 'Created At')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => true,
    ), 'Updated At')
    ->addIndex(
        $installer->getIdxName('easyshippingrules/carrier', array('easyshippingrules_carrier_id')),
        array('easyshippingrules_carrier_id')
    )
    ->addForeignKey(
        $installer->getFkName('easyshippingrules/custom_method', 'easyshippingrules_carrier_id', 'easyshippingrules/carrier', 'easyshippingrules_carrier_id'),
        'easyshippingrules_carrier_id',
        $installer->getTable('easyshippingrules/carrier'),
        'easyshippingrules_carrier_id',
        Varien_Db_Ddl_Table::ACTION_SET_NULL,
        Varien_Db_Ddl_Table::ACTION_SET_NULL
    )
    ->setComment('Easy Shipping Rules - Custom Method');

$installer
    ->getConnection()
    ->createTable($table);

/**
 * Create table 'easyshippingrules_custom_method_store'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('easyshippingrules/custom_method_store'))
    ->addColumn('easyshippingrules_custom_method_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'primary'  => true,
        'unsigned' => true,
        'nullable' => false,
    ), 'Custom Method ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'primary'  => true,
        'unsigned' => true,
        'nullable' => false,
        'default'  => '0',
    ), 'Store ID')
    ->addIndex(
        $installer->getIdxName('easyshippingrules/custom_method_store', array('easyshippingrules_custom_method_id')),
        array('easyshippingrules_custom_method_id')
    )
    ->addIndex(
        $installer->getIdxName('easyshippingrules/custom_method_store', array('store_id')),
        array('store_id')
    )
    ->addForeignKey(
        $installer->getFkName('easyshippingrules/custom_method_store', 'easyshippingrules_custom_method_id', 'easyshippingrules/custom_method', 'easyshippingrules_custom_method_id'),
        'easyshippingrules_custom_method_id',
        $installer->getTable('easyshippingrules/custom_method'),
        'easyshippingrules_custom_method_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $installer->getFkName('easyshippingrules/custom_method_store', 'store_id', 'core/store', 'store_id'),
        'store_id',
        $installer->getTable('core/store'),
        'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Easy Shipping Rules - Custom Method');

$installer
    ->getConnection()
    ->createTable($table);

/**
 * Create table 'easyshippingrules_custom_method_carrier'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('easyshippingrules/custom_method_carrier'))
    ->addColumn('easyshippingrules_carrier_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'primary'  => true,
        'unsigned' => true,
        'nullable' => false,
    ), 'Carrier ID')
    ->addColumn('easyshippingrules_custom_method_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'primary'  => true,
        'unsigned' => true,
        'nullable' => false,
    ), 'Custom Method ID')
    ->addIndex(
        $installer->getIdxName('easyshippingrules/custom_method', array('easyshippingrules_carrier_id')),
        array('easyshippingrules_carrier_id')
    )
    ->addIndex(
        $installer->getIdxName('easyshippingrules/custom_method', array('easyshippingrules_custom_method_id')),
        array('easyshippingrules_custom_method_id')
    )
    ->addForeignKey(
        $installer->getFkName('easyshippingrules/custom_method_carrier', 'easyshippingrules_carrier_id', 'easyshippingrules/carrier', 'easyshippingrules_carrier_id'),
        'easyshippingrules_carrier_id',
        $installer->getTable('easyshippingrules/carrier'),
        'easyshippingrules_carrier_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $installer->getFkName('easyshippingrules/custom_method_carrier', 'easyshippingrules_custom_method_id', 'easyshippingrules/custom_method', 'easyshippingrules_custom_method_id'),
        'easyshippingrules_custom_method_id',
        $installer->getTable('easyshippingrules/custom_method'),
        'easyshippingrules_custom_method_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Easy Shipping Rules - Custom Method');

$installer
    ->getConnection()
    ->createTable($table);

/**
 * Create table 'easyshippingrules_existing_method'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('easyshippingrules/existing_method'))
    ->addColumn('easyshippingrules_existing_method_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary'  => true,
    ), 'Existing Method ID')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 50, array(
        'nullable' => false,
        'default'  => null,
    ), 'Name')
    ->addColumn('carrier_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 50, array(
        'nullable' => false,
        'default'  => null,
    ), 'Carrier Name')
    ->addColumn('method_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 50, array(
        'nullable' => false,
        'default'  => null,
    ), 'Method Name')
    ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_SMALLINT, 6, array(
        'nullable' => true,
        'default'  => 0,
    ), 'Is Active')
    ->addColumn('action', Varien_Db_Ddl_Table::TYPE_VARCHAR, 50, array(
        'nullable' => true,
        'default'  => null,
    ), 'Action')
    ->addColumn('price_action', Varien_Db_Ddl_Table::TYPE_VARCHAR, 50, array(
        'nullable' => true,
        'default'  => null,
    ), 'Price Action')
    ->addColumn('price_percentage', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,2', array(
        'nullable' => true,
        'default'  => null,
    ), 'Price or Percentage')
    ->addColumn('shipping_method_codes', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => true,
        'default'  => null,
    ), 'Shipping Method Codes')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => true,
    ), 'Created At')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => true,
    ), 'Updated At')
    ->setComment('Easy Shipping Rules - Existing Method');

$installer
    ->getConnection()
    ->createTable($table);

/**
 * Create table 'easyshippingrules_existing_method_store'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('easyshippingrules/existing_method_store'))
    ->addColumn('easyshippingrules_existing_method_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'primary'  => true,
        'unsigned' => true,
        'nullable' => false,
    ), 'Existing Method ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'primary'  => true,
        'unsigned' => true,
        'nullable' => false,
        'default'  => '0',
    ), 'Store ID')
    ->addIndex(
        $installer->getIdxName('easyshippingrules/existing_method_store', array('easyshippingrules_existing_method_id')),
        array('easyshippingrules_existing_method_id')
    )
    ->addIndex(
        $installer->getIdxName('easyshippingrules/existing_method_store', array('store_id')),
        array('store_id')
    )
    ->addForeignKey(
        $installer->getFkName('easyshippingrules/existing_method_store', 'easyshippingrules_existing_method_id', 'easyshippingrules/existing_method', 'easyshippingrules_existing_method_id'),
        'easyshippingrules_existing_method_id',
        $installer->getTable('easyshippingrules/existing_method'),
        'easyshippingrules_existing_method_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $installer->getFkName('easyshippingrules/existing_method_store', 'store_id', 'core/store', 'store_id'),
        'store_id',
        $installer->getTable('core/store'),
        'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Easy Shipping Rules - Existing Method');

$installer
    ->getConnection()
    ->createTable($table);

/**
 * Create table 'easyshippingrules_rule'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('easyshippingrules/rule'))
    ->addColumn('easyshippingrules_rule_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary'  => true,
    ), 'Rule ID')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 50, array(
        'nullable' => false,
        'default'  => null,
    ), 'Name')
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
        'default'  => null,
    ), 'Description')
    ->addColumn('conditions_serialized', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
        'default'  => null,
    ), 'Conditions Serialized')
    ->addColumn('price_percentage', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,2', array(
        'nullable' => true,
        'default'  => null,
    ), 'Price or Percentage')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => true,
    ), 'Created At')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => true,
    ), 'Updated At')
    ->setComment('Easy Shipping Rules - Rule');

$installer
    ->getConnection()
    ->createTable($table);

/**
 * Create table 'easyshippingrules_custom_method_rule'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('easyshippingrules/custom_method_rule'))
    ->addColumn('easyshippingrules_custom_method_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'primary'  => true,
        'unsigned' => true,
        'nullable' => false,
    ), 'Custom Method ID')
    ->addColumn('easyshippingrules_rule_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'primary'  => true,
        'unsigned' => true,
        'nullable' => false,
    ), 'Store ID')
    ->addIndex(
        $installer->getIdxName('easyshippingrules/custom_method_rule', array('easyshippingrules_custom_method_id')),
        array('easyshippingrules_custom_method_id')
    )
    ->addIndex(
        $installer->getIdxName('easyshippingrules/custom_method_rule', array('easyshippingrules_rule_id')),
        array('easyshippingrules_rule_id')
    )
    ->addForeignKey(
        $installer->getFkName('easyshippingrules/custom_method_rule', 'easyshippingrules_custom_method_id', 'easyshippingrules/custom_method', 'easyshippingrules_custom_method_id'),
        'easyshippingrules_custom_method_id',
        $installer->getTable('easyshippingrules/custom_method'),
        'easyshippingrules_custom_method_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $installer->getFkName('easyshippingrules/custom_method_rule', 'easyshippingrules_rule_id', 'easyshippingrules/rule', 'easyshippingrules_rule_id'),
        'easyshippingrules_rule_id',
        $installer->getTable('easyshippingrules/rule'),
        'easyshippingrules_rule_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Easy Shipping Rules - Custom Method');

$installer
    ->getConnection()
    ->createTable($table);

/**
 * Create table 'easyshippingrules_existing_method_rule'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('easyshippingrules/existing_method_rule'))
    ->addColumn('easyshippingrules_existing_method_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'primary'  => true,
        'unsigned' => true,
        'nullable' => false,
    ), 'Existing Method ID')
    ->addColumn('easyshippingrules_rule_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'primary'  => true,
        'unsigned' => true,
        'nullable' => false,
    ), 'Store ID')
    ->addIndex(
        $installer->getIdxName('easyshippingrules/existing_method_rule', array('easyshippingrules_existing_method_id')),
        array('easyshippingrules_existing_method_id')
    )
    ->addIndex(
        $installer->getIdxName('easyshippingrules/existing_method_rule', array('easyshippingrules_rule_id')),
        array('easyshippingrules_rule_id')
    )
    ->addForeignKey(
        $installer->getFkName('easyshippingrules/existing_method_rule', 'easyshippingrules_existing_method_id', 'easyshippingrules/existing_method', 'easyshippingrules_existing_method_id'),
        'easyshippingrules_existing_method_id',
        $installer->getTable('easyshippingrules/existing_method'),
        'easyshippingrules_existing_method_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $installer->getFkName('easyshippingrules/existing_method_rule', 'easyshippingrules_rule_id', 'easyshippingrules/rule', 'easyshippingrules_rule_id'),
        'easyshippingrules_rule_id',
        $installer->getTable('easyshippingrules/rule'),
        'easyshippingrules_rule_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Easy Shipping Rules - Existing Method');

$installer
    ->getConnection()
    ->createTable($table);

$installer->endSetup();
