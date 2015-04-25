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
class MatheusGontijo_EasyShippingRules_Model_Rule_Condition_Product_Found
    extends Mage_SalesRule_Model_Rule_Condition_Product_Found
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->setType('easyshippingrules/rule_condition_product_found');
    }

    public function getNewChildSelectOptions()
    {
        $productCondition  = Mage::getModel('easyshippingrules/rule_condition_product');
        $productAttributes = $productCondition->loadAttributeOptions()->getAttributeOption();

        $pAttributes = array();
        $cAttributes = array();

        foreach ($productAttributes as $code => $label) {
            if (strpos($code, 'quote_item_') === 0) {
                $cAttributes[] = array(
                    'value' => 'easyshippingrules/rule_condition_product|' . $code,
                    'label' => $label,
                );
            } else {
                $pAttributes[] = array(
                    'value' => 'easyshippingrules/rule_condition_product|' . $code,
                    'label' => $label,
                );
            }
        }

        $conditions = Mage_Rule_Model_Condition_Abstract::getNewChildSelectOptions();
        $conditions = array_merge_recursive($conditions, array(
            array(
                'label' => Mage::helper('easyshippingrules')->__('Cart Item Attribute'),
                'value' => $cAttributes,
            ),
            array(
                'label' => Mage::helper('easyshippingrules')->__('Product Attribute'),
                'value' => $pAttributes,
            ),
        ));

        return $conditions;
    }
}
