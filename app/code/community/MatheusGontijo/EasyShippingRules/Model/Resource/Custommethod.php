<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * Custom Method resource
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Model_Resource_Custommethod
    extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init('easyshippingrules/custom_method', 'easyshippingrules_custom_method_id');
    }

    /**
     * Perform operations after object load
     *
     * @param Mage_Core_Model_Abstract $object
     *
     * @return Mage_Core_Model_Resource_Db_Abstract
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());
            $object->setStoreId($stores);
        }

        return parent::_afterLoad($object);
    }

    /**
     * Prepare some data before save processing
     *
     * @param Mage_Core_Model_Abstract $object
     *
     * @return MatheusGontijo_EasyShippingRules_Model_Resource_Custommethod
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        if (!$object->getPricePercentage()) {
            $object->setPricePercentage(null);
        }

        if (!$object->getId()) {
            $object->setCreatedAt(Varien_Date::now());
        } else {
            $object->setUpdatedAt(Varien_Date::now());
        }

        return $this;
    }

    /**
     * Assign shipping rule to store views and linking rules
     *
     * @param Mage_Core_Model_Abstract $object
     *
     * @return Mage_Core_Model_Resource_Db_Abstract
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $this->_addLinkStores($object);

        if ($object->hasCustomMethodRules()) {
            $rules = Mage::helper('adminhtml/js')
                ->decodeGridSerializedInput($object->getCustomMethodRules());
            $this->_addLinkRules($object, $rules);
        }

        return parent::_afterSave($object);
    }

    /**
     * Retrieve link rules ids
     *
     * @param Mage_Core_Model_Abstract $object
     *
     * @return array
     */
    public function getLinkRuleIds(Mage_Core_Model_Abstract $object)
    {
        $select = $this
            ->_getReadAdapter()
            ->select()
            ->from(
                $this->getTable('easyshippingrules/custom_method_rule'),
                array('easyshippingrules_rule_id')
            )
            ->where('easyshippingrules_custom_method_id = ?', $object->getId());

        return $this->_getReadAdapter()->fetchCol($select);
    }

    /**
     * Add link stores for custom method
     *
     * @param Mage_Core_Model_Abstract $object
     *
     * @return MatheusGontijo_EasyShippingRules_Model_Resource_Custommethod
     */
    protected function _addLinkStores(Mage_Core_Model_Abstract $object)
    {
        $oldStores = $this->lookupStoreIds($object->getId());
        $newStores = (array) $object->getStores();

        if (empty($newStores)) {
            $newStores = (array) $object->getStoreId();
        }

        $table  = $this->getTable('easyshippingrules/custom_method_store');

        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);

        if ($delete) {
            $where = array(
                'easyshippingrules_custom_method_id = ?' => $object->getId(),
                'store_id IN (?)'                        => $delete,
            );

            $this->_getWriteAdapter()->delete($table, $where);
        }

        if ($insert) {
            $data = array();
            foreach ($insert as $storeId) {
                $data[] = array(
                    'easyshippingrules_custom_method_id' => $object->getId(),
                    'store_id'                           => $storeId,
                );
            }

            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }

        return $this;
    }

    /**
     * Add link rules for custom method
     *
     * @param Mage_Core_Model_Abstract $object
     * @param array                    $rules
     *
     * @return MatheusGontijo_EasyShippingRules_Model_Resource_Custommethod
     */
    protected function _addLinkRules(Mage_Core_Model_Abstract $object, array $rules)
    {
        $oldRuleIds = $this->getLinkRuleIds($object);
        $newRuleIds = $rules;

        $insertIds = array_diff($newRuleIds, $oldRuleIds);
        $deleteIds = array_diff($oldRuleIds, $newRuleIds);

        if ($deleteIds) {
            $this->_getWriteAdapter()->delete($this->getTable('easyshippingrules/custom_method_rule'), array(
                'easyshippingrules_custom_method_id = ?' => $object->getId(),
                'easyshippingrules_rule_id IN (?)'       => $deleteIds
            ));
        }

        if ($insertIds) {
            $data = array();
            foreach ($insertIds as $ruleId) {
                $data[] = array(
                    'easyshippingrules_custom_method_id' => $object->getId(),
                    'easyshippingrules_rule_id'          => $ruleId,
                );
            }

            $this->_getWriteAdapter()->insertMultiple(
                $this->getTable('easyshippingrules/custom_method_rule'),
                $data
            );
        }

        return $this;
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $methodId
     *
     * @return array
     */
    public function lookupStoreIds($methodId)
    {
        $adapter = $this->_getReadAdapter();

        $select = $adapter
            ->select()
            ->from(
                $this->getTable('easyshippingrules/custom_method_store'),
                array('store_id')
            )
            ->where('easyshippingrules_custom_method_id = ?', $methodId);

        return $adapter->fetchCol($select);
    }
}
