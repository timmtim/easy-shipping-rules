<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * System Config Source Rule model
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Model_System_Config_Source_Rule
{
    /**
     * Rules values
     */
    protected $_rules;

    /**
     * Retrieve rule values for form
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->_rules) {
            $rules = Mage::getResourceModel('easyshippingrules/rule_collection')
                ->toOptionArray();

            array_unshift($rules, array('value' => '', 'label' => ''));

            $this->_rules = $rules;
        }

        return $this->_rules;
    }
}
