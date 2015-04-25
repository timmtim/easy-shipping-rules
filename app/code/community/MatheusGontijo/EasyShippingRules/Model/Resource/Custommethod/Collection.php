<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * Custom Method collection
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Model_Resource_Custommethod_Collection
    extends Mage_Rule_Model_Resource_Rule_Collection_Abstract
{
    /**
     * Initialize collection model
     */
    protected function _construct()
    {
        $this->_init('easyshippingrules/custommethod');
    }

    /**
     * Redeclare before load method for adding event
     *
     * @return MatheusGontijo_EasyShippingRules_Model_Resource_Custommethod_Collection
     */
    protected function _beforeLoad()
    {
        parent::_beforeLoad();

        $this->getSelect()->joinLeft(
            array('c' => $this->getResource()->getTable('easyshippingrules/carrier')),
            'main_table.easyshippingrules_carrier_id = c.easyshippingrules_carrier_id',
            array('name AS carrier_name')
        );

        return $this;
    }

    /**
     * Prepare for applying available custom methods
     *
     * @return $this
     */
    public function prepareAvailableMethods()
    {
        $this->addFieldToFilter('main_table.easyshippingrules_carrier_id', array('notnull' => true));
        $this->addFieldToFilter('c.is_active', true);
        $this->addFieldToFilter('main_table.is_active', true);

        $read = $this->getResource()->getReadConnection();

        $storeIds = array(
            Mage_Core_Model_App::ADMIN_STORE_ID,
            Mage::app()->getStore(true)->getId(),
        );

        $this
            ->getSelect()
            ->join(
                array('custommethod_store' => $this->getTable('easyshippingrules/custom_method_store')),
                $read->quoteInto(
                    'main_table.easyshippingrules_custom_method_id = custommethod_store.easyshippingrules_custom_method_id ' .
                    'AND custommethod_store.store_id IN (?)', $storeIds
                ),
                array()
            );

        return $this;
    }
}
