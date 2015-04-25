<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * Adminhtml Easy Shipping Rules Existing Methods Edit Tab Rules block
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Block_Adminhtml_Easyshippingrules_Existingmethod_Edit_Tab_Rules
    extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->setId('rules_grid');
        $this->setUseAjax(true);

        if ($this->_getExistingMethod()->getLinkRuleIds()) {
            $this->setDefaultFilter(array('in_rules' => 1));
        }
    }

    /**
     * Prepare collection
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('easyshippingrules/rule')
            ->getCollection();

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Add columns to grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('in_rules', array(
            'header_css_class' => 'a-center',
            'type'             => 'checkbox',
            'name'             => 'in_rules',
            'values'           => $this->_getSelectedRules(),
            'align'            => 'center',
            'index'            => 'easyshippingrules_rule_id',
        ));

        $this->addColumn('easyshippingrules_rule_id', array(
            'header'   => $this->__('ID'),
            'sortable' => true,
            'width'    => 60,
            'index'    => 'easyshippingrules_rule_id',
        ));

        $this->addColumn('name', array(
            'header' => $this->__('Name'),
            'index'  => 'name',
        ));

        return parent::_prepareColumns();
    }

    /**
     * Add filter
     *
     * @param object $column
     *
     * @return MatheusGontijo_EasyShippingRules_Block_Adminhtml_Easyshippingrules_Existingmethod_Edit_Tab_Rules
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_rules') {

            $ruleIds = $this->_getSelectedRules();

            if (empty($ruleIds)) {
                $ruleIds = 0;
            }

            if ($column->getFilter()->getValue()) {
                $this
                    ->getCollection()
                    ->addFieldToFilter(
                        'main_table.easyshippingrules_rule_id',
                        array('in' => $ruleIds)
                    );
            } else {
                if ($ruleIds) {
                    $this
                        ->getCollection()
                        ->addFieldToFilter(
                            'main_table.easyshippingrules_rule_id',
                            array('nin' => $ruleIds)
                        );
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }

        return $this;
    }

    /**
     * Rerieve grid URL
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getData('grid_url')
            ? $this->getData('grid_url')
            : $this->getUrl('*/*/rulesGrid', array('_current' => true));
    }

    /**
     * Retrieve currently edited method model
     *
     * @return MatheusGontijo_EasyShippingRules_Model_Existingmethod
     */
    protected function _getExistingMethod()
    {
        return Mage::registry('current_easyshippingrules_existing_method');
    }

    /**
     * Retrieve selected rules
     *
     * @return array
     */
    protected function _getSelectedRules()
    {
        $rules = $this->getRules();

        if (!is_array($rules)) {
            $rules = $this->_getExistingMethod()->getLinkRuleIds();
        }

        return $rules;
    }

    /**
     * Retrieve selected rules
     *
     * @return array
     */
    public function getSelectedRules()
    {
        return $this->_getSelectedRules();
    }
}
