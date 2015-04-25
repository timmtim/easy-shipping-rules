<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * Adminhtml Easy Shipping Rules Rule Grid block
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Block_Adminhtml_Easyshippingrules_Rule_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->setId('easyshippingrules_rule_grid');
        $this->setUseAjax(true);
        $this->setDefaultSort('easyshippingrules_rule_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
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
     * @return Mage_Adminhtml_Block_Promo_Quote_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('easyshippingrules_rule_id', array(
            'header' => $this->__('ID'),
            'align'  => 'right',
            'width'  => '50px',
            'index'  => 'easyshippingrules_rule_id',
        ));

        $this->addColumn('name', array(
            'header' => $this->__('Rule Name'),
            'align'  => 'left',
            'index'  => 'name',
        ));

        $this->addColumn('price_percentage', array(
            'header'  => $this->__('Price / Percentage'),
            'align'   => 'right',
            'width'   => '80px',
            'type'    => 'currency',
            'currency_code' => Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
            'index'   => 'price_percentage',
        ));

        $this->addColumn('created_at', array(
            'header'  => $this->__('Created At'),
            'type' => 'datetime',
            'width'   => '150px',
            'index'   => 'created_at',
        ));

        $this->addColumn('updated_at', array(
            'header'  => $this->__('Updated At'),
            'type' => 'datetime',
            'width'   => '150px',
            'index'   => 'updated_at',
        ));

        parent::_prepareColumns();
        
        return $this;
    }

    /**
     * Prepare grid massaction actions
     *
     * @return MatheusGontijo_EasyShippingRules_Block_Adminhtml_Easyshippingrules_Rule_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('easyshippingrules_rule_id');

        $this
            ->getMassactionBlock()
            ->setFormFieldName('rule');

        $this
            ->getMassactionBlock()
            ->addItem('delete', array(
                 'label'   => Mage::helper('adminhtml')->__('Delete'),
                 'url'     => $this->getUrl('*/*/massDelete'),
                 'confirm' => Mage::helper('easyshippingrules')->__('Are you sure?')
            ));

        $this
            ->getMassactionBlock()
            ->addItem('price_percentage', array(
                'label' => Mage::helper('easyshippingrules')->__('Update Price / Percentage'),
                'url'   => $this->getUrl('*/*/massUpdatePricePercentage', array('_current' => true)),
                'additional' => array('visibility'  => array(
                    'name'   => 'price_percentage',
                    'type'   => 'text',
                    'class'  => 'validate-currency-dollar',
                    'label'  => Mage::helper('easyshippingrules')->__('Price / Percentage'),
                ),
            )));

        return $this;
    }

    /**
     * Retrieve grid URL
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    /**
     * Retrieve row click URL
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}
