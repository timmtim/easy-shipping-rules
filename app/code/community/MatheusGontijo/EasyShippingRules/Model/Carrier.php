<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * Carrier model
 *
 * @method string getName()
 * @method MatheusGontijo_EasyShippingRules_Model_Carrier setName(string $value)
 * @method int getIsActive()
 * @method MatheusGontijo_EasyShippingRules_Model_Carrier setIsActive(int $value)
 * @method int getPosition()
 * @method MatheusGontijo_EasyShippingRules_Model_Carrier setPosition(int $value)
 * @method string getCreatedAt()
 * @method MatheusGontijo_EasyShippingRules_Model_Carrier setCreatedAt(string $value)
 * @method string getUpdatedAt()
 * @method MatheusGontijo_EasyShippingRules_Model_Carrier setUpdatedAt(string $value)
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Model_Carrier
    extends Mage_Core_Model_Abstract
{
    /**
     * Constructor
     */
    protected function _construct()
    {
        parent::_construct();
        
        $this->_init('easyshippingrules/carrier');
        $this->setIdFieldName('easyshippingrules_carrier_id');
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
}
