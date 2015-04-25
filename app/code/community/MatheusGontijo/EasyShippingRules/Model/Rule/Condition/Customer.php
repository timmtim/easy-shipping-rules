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
class MatheusGontijo_EasyShippingRules_Model_Rule_Condition_Customer
    extends MatheusGontijo_EasyShippingRules_Model_Rule_Condition_Product
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->setType('easyshippingrules/rule_condition_customer');
    }

    /**
     * Retrieve attribute object
     *
     * @return Mage_Customer_Model_Resource_Eav_Attribute
     */
    public function getAttributeObject()
    {
        try {
            $object = Mage::getSingleton('eav/config')
                ->getAttribute('customer', $this->getAttribute());
        } catch (Exception $e) {
            $object = new Varien_Object();
            $object->setEntity(Mage::getResourceSingleton('customer/customer'))
                ->setFrontendInput('text');
        }

        return $object;
    }

    /**
     * Load attribute options
     *
     * @return MatheusGontijo_EasyShippingRules_Model_Rule_Condition_Customer
     */
    public function loadAttributeOptions()
    {
        $productAttributes = Mage::getResourceSingleton('customer/customer')
            ->loadAllAttributes()
            ->getAttributesByCode();

        $attributes = array();
        foreach ($productAttributes as $attribute) {
            if ($attribute->getFrontendLabel()) {
                $attributes[$attribute->getAttributeCode()] = $attribute->getFrontendLabel();
            }
        }

        asort($attributes);

        $this->setAttributeOption($attributes);

        return $this;
    }

    /**
     * Validate Customer Rule Condition
     *
     * @param Varien_Object $object
     *
     * @return bool
     */
    public function validate(Varien_Object $object)
    {
        $customer = Mage::getModel('customer/customer')
            ->load($object->getCustomerId());

        if (!$customer->getId()) {
            return false;
        }

        return Mage_Rule_Model_Condition_Product_Abstract::validate($customer);
    }
}
