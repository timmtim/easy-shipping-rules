<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * Rule resource
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Model_Resource_Rule
    extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init('easyshippingrules/rule', 'easyshippingrules_rule_id');
    }

    /**
     * Prepare some data before save processing
     *
     * @param Mage_Core_Model_Abstract $object
     *
     * @return MatheusGontijo_EasyShippingRules_Model_Resource_Custommethod
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        if (!$object->getPricePercentage()) {
            $object->setPricePercentage(null);
        }

        if (!$object->getId()) {
            $object->setCreatedAt(Varien_Date::now());
        } else {
            $object->setUpdatedAt(Varien_Date::now());
        }

        return $this;
    }
}
