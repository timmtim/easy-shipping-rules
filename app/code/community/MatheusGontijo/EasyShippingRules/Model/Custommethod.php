<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * Custom Method model
 *
 * @method string getName()
 * @method MatheusGontijo_EasyShippingRules_Model_Custommethod setEasyshippingrulesCarrierId(int $value)
 * @method int getEasyshippingrulesCarrierId()
 * @method MatheusGontijo_EasyShippingRules_Model_Custommethod setName(string $value)
 * @method string getDescription()
 * @method MatheusGontijo_EasyShippingRules_Model_Custommethod setDescription(string $value)
 * @method int getIsActive()
 * @method MatheusGontijo_EasyShippingRules_Model_Custommethod setIsActive(int $value)
 * @method string getConditionsSerialized()
 * @method MatheusGontijo_EasyShippingRules_Model_Custommethod setConditionsSerialized(string $value)
 * @method string getPricePercentage()
 * @method MatheusGontijo_EasyShippingRules_Model_Custommethod setPricePercentage(string $value)
 * @method string getCreatedAt()
 * @method MatheusGontijo_EasyShippingRules_Model_Custommethod setCreatedAt(string $value)
 * @method string getUpdatedAt()
 * @method MatheusGontijo_EasyShippingRules_Model_Custommethod setUpdatedAt(string $value)
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Model_Custommethod
    extends Mage_Core_Model_Abstract
{
    /**
     * Constructor
     */
    protected function _construct()
    {
        parent::_construct();
        
        $this->_init('easyshippingrules/custommethod');
        $this->setIdFieldName('easyshippingrules_custom_method_id');
    }

    /**
     * Processing object before save data
     *
     * @return Mage_Core_Model_Abstract
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();

        if (!$this->getEasyshippingrulesCarrierId()) {
            $this->setEasyshippingrulesCarrierId(null);
        }

        $storeId = $this->getStoreId();
        if ($storeId === null) {
            $this->setStoreId(Mage::app()->getStore()->getId());
        }

        return $this;
    }

    /**
     * Retrieve array of custom rules
     *
     * @return array
     */
    public function getRules()
    {
        if (!$this->hasRules()) {
            $rules = array();

            $collection = Mage::getModel('easyshippingrules/rule')
                ->getCollection()
                ->addCustomMethodFilter($this);

            foreach ($collection as $rule) {
                $rules[] = $rule;
            }

            $this->setRules($rules);
        }

        return $this->getData('rules');
    }

    /**
     * Retrieve array of shipping rule ids
     *
     * @return array
     */
    public function getLinkRuleIds()
    {
        if (!$this->hasLinkRuleIds()) {
            $ruleIds = $this->getResource()->getLinkRuleIds($this);
            $this->setLinkRuleIds($ruleIds);
        }
        
        return $this->getData('link_rule_ids');
    }
}
