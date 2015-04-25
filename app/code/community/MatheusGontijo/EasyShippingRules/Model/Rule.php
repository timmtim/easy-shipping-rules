<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * Rule model
 *
 * @method string getExistingMethodName()
 * @method MatheusGontijo_EasyShippingRules_Model_Rule setExistingMethodName(string $value)
 * @method string getName()
 * @method MatheusGontijo_EasyShippingRules_Model_Rule setName(string $value)
 * @method string getAction()
 * @method MatheusGontijo_EasyShippingRules_Model_Rule setAction(string $value)
 * @method string getPriceAction()
 * @method MatheusGontijo_EasyShippingRules_Model_Rule setPriceAction(string $value)
 * @method float getPricePercentage()
 * @method MatheusGontijo_EasyShippingRules_Model_Rule setPricePercentage(float $value)
 * @method string getShippingMethodCodes()
 * @method MatheusGontijo_EasyShippingRules_Model_Rule setShippingMethodCodes(string $value)
 * @method string getCreatedAt()
 * @method MatheusGontijo_EasyShippingRules_Model_Rule setCreatedAt(string $value)
 * @method string getUpdatedAt()
 * @method MatheusGontijo_EasyShippingRules_Model_Rule setUpdatedAt(string $value)
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Model_Rule
    extends Mage_Rule_Model_Abstract
{
    /**
     * Constructor
     */
    protected function _construct()
    {
        parent::_construct();
        
        $this->_init('easyshippingrules/rule');
        $this->setIdFieldName('easyshippingrules_rule_id');
    }

    /**
     * Get rule condition combine model instance
     *
     * @return MatheusGontijo_EasyShippingRules_Model_Rule_Condition_Combine
     */
    public function getConditionsInstance()
    {
        return Mage::getModel('easyshippingrules/rule_condition_combine');
    }

    /**
     * Overwrite parent method to break actions functionality
     *
     * @return boolean
     */
    public function getActionsInstance()
    {
        return false;
    }

    /**
     * Overwrite parent method to break actions functionality
     *
     * @return boolean
     */
    public function getActions()
    {
        return false;
    }
}
