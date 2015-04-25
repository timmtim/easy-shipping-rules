<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * Adminhtml Easy Shipping Rules Widget Chooser Rule block
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Block_Adminhtml_Easyshippingrules_Widget_Chooser_Rule
    extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Form const
     */
    const FORM_NAME = 'rule_conditions_fieldset';

    /**
     * Constructor
     *
     * @param array $arguments
     */
    public function __construct($arguments = array())
    {
        parent::__construct($arguments);

        $this->setId('ruleChooserGrid_' . $this->getId());

        $form = self::FORM_NAME;

        $this->setRowClickCallback("$form.chooserGridRowClick.bind($form)");
        $this->setCheckboxCheckCallback("$form.chooserGridCheckboxCheck.bind($form)");
        $this->setRowInitCallback("$form.chooserGridRowInit.bind($form)");
        $this->setDefaultSort('grid_easyshippingrules_rule_id');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
    }

    /**
     * Add filter
     *
     * @param object $column
     *
     * @return MatheusGontijo_EasyShippingRules_Block_Adminhtml_Easyshippingrules_Widget_Chooser_Rule
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in rule flag
        if ($column->getId() == 'in_rules') {

            $selected = $this->_getSelectedRules();

            if (empty($selected)) {
                $selected = '';
            }

            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('easyshippingrules_rule_id', array('in' => $selected));
            } else {
                $this->getCollection()->addFieldToFilter('easyshippingrules_rule_id', array('nin' => $selected));
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }

        return $this;
    }

    /**
     * Prepare grid collection object
     *
     * @return Mage_Adminhtml_Block_Promo_Quote_Grid
     */
    protected function _prepareCollection()
    {
        /** @var $collection MatheusGontijo_EasyShippingRules_Model_Mysql4_Rule_Collection */
        $collection = Mage::getModel('easyshippingrules/rule')
            ->getResourceCollection();

        $this->setCollection($collection);

        parent::_prepareCollection();

        return $this;
    }

    /**
     * Add grid columns
     *
     * @return MatheusGontijo_EasyShippingRules_Block_Adminhtml_Easyshippingrules_Widget_Chooser_Rule
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

        /**
         * grid_ prefix for not generating field conflict on submit form
         */

        $this->addColumn('grid_easyshippingrules_rule_id', array(
            'header' => $this->__('ID'),
            'align'  => 'right',
            'width'  => '50px',
            'index'  => 'easyshippingrules_rule_id',
        ));

        $this->addColumn('grid_name', array(
            'header' => $this->__('Rule Name'),
            'align'  => 'left',
            'index'  => 'name',
        ));

        $this->addColumn('grid_price_percentage', array(
            'header'  => $this->__('Price / Percentage'),
            'align'   => 'right',
            'width'   => '80px',
            'type'    => 'number',
            'index'   => 'price_percentage',
        ));

        $this->addColumn('grid_is_active', array(
            'header'  => Mage::helper('adminhtml')->__('Status'),
            'align'   => 'left',
            'width'   => '80px',
            'index'   => 'is_active',
            'type'    => 'options',
            'options' => Mage::getModel('easyshippingrules/system_status')->toArray(),
        ));

        $this->addColumn('grid_created_at', array(
            'header'  => $this->__('Created At'),
            'type' => 'datetime',
            'width'   => '150px',
            'index'   => 'created_at',
        ));

        $this->addColumn('grid_updated_at', array(
            'header'  => $this->__('Updated At'),
            'type' => 'datetime',
            'width'   => '150px',
            'index'   => 'updated_at',
        ));

        parent::_prepareColumns();

        return $this;
    }

    /**
     * Retrieve grid URL
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/chooser', array('_current' => true));
    }

    /**
     * Retrieve selected rules
     *
     * @return mixed
     */
    protected function _getSelectedRules()
    {
        return $this->getRequest()->getPost('selected', array());
    }
}
