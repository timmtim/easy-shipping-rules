<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * System Action model
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Model_System_Action
{
    /**
     * Retrieve action action values for form
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            MatheusGontijo_EasyShippingRules_Model_Existingmethod::OVERWRITE_ACTION => Mage::helper('easyshippingrules')->__('Overwrite'),
            MatheusGontijo_EasyShippingRules_Model_Existingmethod::DISCOUNT_ACTION  => Mage::helper('easyshippingrules')->__('Discount'),
            MatheusGontijo_EasyShippingRules_Model_Existingmethod::SURCHARGE_ACTION => Mage::helper('easyshippingrules')->__('Surcharge'),
            MatheusGontijo_EasyShippingRules_Model_Existingmethod::HIDE_ACTION      => Mage::helper('easyshippingrules')->__('Hide'),
        );
    }
}
