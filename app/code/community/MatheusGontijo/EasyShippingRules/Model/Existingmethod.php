<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * Existing Method model
 *
 * @method string getName()
 * @method MatheusGontijo_EasyShippingRules_Model_Existingmethod setName(string $value)
 * @method string getCarrierName()
 * @method MatheusGontijo_EasyShippingRules_Model_Existingmethod setCarrierName(string $value)
 * @method string getMethodName()
 * @method MatheusGontijo_EasyShippingRules_Model_Existingmethod setMethodName(string $value)
 * @method int getIsActive()
 * @method MatheusGontijo_EasyShippingRules_Model_Existingmethod setIsActive(int $value)
 * @method string getAction()
 * @method MatheusGontijo_EasyShippingRules_Model_Existingmethod setAction(string $value)
 * @method string getPriceAction()
 * @method MatheusGontijo_EasyShippingRules_Model_Existingmethod setPriceAction(string $value)
 * @method float getPricePercentage()
 * @method MatheusGontijo_EasyShippingRules_Model_Existingmethod setPricePercentage(float $value)
 * @method string getShippingMethodCodes()
 * @method MatheusGontijo_EasyShippingRules_Model_Existingmethod setShippingMethodCodes(string $value)
 * @method string getCreatedAt()
 * @method MatheusGontijo_EasyShippingRules_Model_Existingmethod setCreatedAt(string $value)
 * @method string getUpdatedAt()
 * @method MatheusGontijo_EasyShippingRules_Model_Existingmethod setUpdatedAt(string $value)
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Model_Existingmethod
    extends Mage_Core_Model_Abstract
{
    /**
     * All shipping methods option
     */
    const ALL_SHIPPING_METHODS_OPTION = 'all_shipping_methods';

    /**
     * Actions to apply
     */
    const OVERWRITE_ACTION = 'overwrite';
    const SURCHARGE_ACTION = 'add_surcharge';
    const DISCOUNT_ACTION  = 'add_discount';
    const HIDE_ACTION      = 'hide';

    /**
     * Constructor
     */
    protected function _construct()
    {
        parent::_construct();
        
        $this->_init('easyshippingrules/existingmethod');
        $this->setIdFieldName('easyshippingrules_existing_method_id');
    }

    /**
     * Processing object before save data
     *
     * @return Mage_Core_Model_Abstract
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();

        $storeId = $this->getStoreId();
        if ($storeId === null) {
            $this->setStoreId(Mage::app()->getStore()->getId());
        }

        return $this;
    }

    /**
     * Retrieve array of existing rules
     *
     * @return array
     */
    public function getRules()
    {
        if (!$this->hasRules()) {
            $rules = array();

            $collection = Mage::getModel('easyshippingrules/rule')
                ->getCollection()
                ->addExistingMethodFilter($this);

            foreach ($collection as $rule) {
                $rules[] = $rule;
            }

            $this->setRules($rules);
        }

        return $this->getData('rules');
    }

    /**
     * Retrieve array of existing shipping rule ids
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

    /**
     * Return formatted shipping_method_codes
     *
     * return array
     */
    public function getShippingMethodCodesFormatted()
    {
        return explode(',', $this->getShippingMethodCodes());
    }
}
