<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * Existing Method collection
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Model_Resource_Existingmethod_Collection
    extends Mage_Rule_Model_Resource_Rule_Collection_Abstract
{
    /**
     * Initialize collection model
     */
    protected function _construct()
    {
        $this->_init('easyshippingrules/existingmethod');
    }

    /**
     * Prepare for applying available existing methods
     *
     * @return $this
     */
    public function prepareAvailableMethods()
    {
        $this->addFieldToFilter('main_table.is_active', true);

        $read = $this->getResource()->getReadConnection();

        $storeIds = array(
            Mage_Core_Model_App::ADMIN_STORE_ID,
            Mage::app()->getStore(true)->getId(),
        );

        $this
            ->getSelect()
            ->join(
                array('existingmethod_store' => $this->getTable('easyshippingrules/existing_method_store')),
                $read->quoteInto(
                    'main_table.easyshippingrules_existing_method_id = existingmethod_store.easyshippingrules_existing_method_id ' .
                    'AND existingmethod_store.store_id IN (?)', $storeIds
                ),
                array()
            );

        return $this;
    }
}
