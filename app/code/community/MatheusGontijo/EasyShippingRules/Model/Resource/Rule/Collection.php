<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * Rule collection
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Model_Resource_Rule_Collection
    extends Mage_Rule_Model_Resource_Rule_Collection_Abstract
{
    /**
     * Initialize collection model
     */
    protected function _construct()
    {
        $this->_init('easyshippingrules/rule');
    }

    /**
     * Add custom method filter
     *
     * @param MatheusGontijo_EasyShippingRules_Model_Custommethod $method
     *
     * @return MatheusGontijo_EasyShippingRules_Model_Resource_Rule_Collection
     */
    public function addCustomMethodFilter(MatheusGontijo_EasyShippingRules_Model_Custommethod $method)
    {
        $read = $this->getResource()->getReadConnection();

        $this
            ->getSelect()
            ->join(
                array('custommethod_rule' => $this->getTable('easyshippingrules/custom_method_rule')),
                $read->quoteInto(
                    'main_table.easyshippingrules_rule_id = custommethod_rule.easyshippingrules_rule_id ' .
                    'AND custommethod_rule.easyshippingrules_custom_method_id = ?', $method->getId()
                ),
                array()
            );

        return $this;
    }

    /**
     * Add existing method filter
     *
     * @param MatheusGontijo_EasyShippingRules_Model_Existingmethod $method
     *
     * @return MatheusGontijo_EasyShippingRules_Model_Resource_Rule_Collection
     */
    public function addExistingMethodFilter(MatheusGontijo_EasyShippingRules_Model_Existingmethod $method)
    {
        $read = $this->getResource()->getReadConnection();

        $this
            ->getSelect()
            ->join(
                array('existingmethod_rule' => $this->getTable('easyshippingrules/existing_method_rule')),
                $read->quoteInto(
                    'main_table.easyshippingrules_rule_id = existingmethod_rule.easyshippingrules_rule_id ' .
                    'AND existingmethod_rule.easyshippingrules_existing_method_id = ?', $method->getId()
                ),
                array()
            );

        return $this;
    }

    /**
     * Convert collection items to select options array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return parent::_toOptionArray('easyshippingrules_rule_id', 'name');
    }
}
