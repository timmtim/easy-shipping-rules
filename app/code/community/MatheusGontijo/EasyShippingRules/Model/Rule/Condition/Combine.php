<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Model_Rule_Condition_Combine
    extends Mage_Rule_Model_Condition_Combine
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->setType('easyshippingrules/rule_condition_combine');
    }

    /**
     * Add custom conditions selectors
     *
     * @return array
     */
    public function getNewChildSelectOptions()
    {
        $conditions = parent::getNewChildSelectOptions();
        $conditions = array_merge_recursive($conditions, array(
            array(
                'value' => 'easyshippingrules/rule_condition_combine',
                'label' => Mage::helper('easyshippingrules')->__('Conditions combination'),
            ),
            array(
                'value' => 'easyshippingrules/rule_condition_product_found',
                'label' => Mage::helper('easyshippingrules')->__('Product attribute combination'),
            ),
            array(
                'value' => 'easyshippingrules/rule_condition_rule',
                'label' => Mage::helper('easyshippingrules')->__('Shipping Rule'),
            ),
            array(
                'value' => 'easyshippingrules/rule_condition_programmaticmethod',
                'label' => Mage::helper('easyshippingrules')->__('Programmatic Method'),
            ),
            array(
                'label' => Mage::helper('easyshippingrules')->__('Cart Attribute'),
                'value' => $this->_getAddressAttributesData(),
            ),
            array(
                'label' => Mage::helper('easyshippingrules')->__('Customer attribute'),
                'value' => $this->_getCustomerAttributesData(),
            ),
        ));

        return $conditions;
    }

    /**
     * Retrieve address attribute data
     *
     * @return array
     */
    protected function _getAddressAttributesData()
    {
        $addressCondition  = Mage::getModel('easyshippingrules/rule_condition_address');
        $addressAttributes = $addressCondition->loadAttributeOptions()->getAttributeOption();

        $addressAttributesData = array();
        foreach ($addressAttributes as $code => $label) {
            $addressAttributesData[] = array(
                'value' => 'easyshippingrules/rule_condition_address|' . $code,
                'label' => $label,
            );
        }

        return $addressAttributesData;
    }

    /**
     * Retrieve customer attribute data
     *
     * @return array
     */
    protected function _getCustomerAttributesData()
    {
        $customerCondition  = Mage::getModel('easyshippingrules/rule_condition_customer');
        $customerAttributes = $customerCondition->loadAttributeOptions()->getAttributeOption();

        $customerAttributesData = array();
        foreach ($customerAttributes as $code => $label) {
            $customerAttributesData[] = array(
                'value' => 'easyshippingrules/rule_condition_customer|' . $code,
                'label' => $label,
            );
        }

        return $customerAttributesData;
    }
}
