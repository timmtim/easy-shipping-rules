<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * Adminhtml Easy Shipping Rules Carrier Grid block
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Block_Adminhtml_Easyshippingrules_Carrier_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->setId('easyshippingrules_carrier_grid');
        $this->setUseAjax(true);
        $this->setDefaultSort('easyshippingrules_carrier_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Prepare grid collection object
     *
     * @return MatheusGontijo_EasyShippingRules_Block_Adminhtml_Easyshippingrules_Carrier_Grid
     */
    protected function _prepareCollection()
    {
        /** @var $collection MatheusGontijo_EasyShippingRules_Model_Mysql4_Carrier_Collection */
        $collection = Mage::getModel('easyshippingrules/carrier')
            ->getResourceCollection();
        
        $this->setCollection($collection);

        parent::_prepareCollection();

        return $this;
    }

    /**
     * Add grid columns
     *
     * @return MatheusGontijo_EasyShippingRules_Block_Adminhtml_Easyshippingrules_Carrier_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('easyshippingrules_carrier_id', array(
            'header' => $this->__('ID'),
            'align'  => 'right',
            'width'  => '50px',
            'type'   => 'number',
            'index'  => 'easyshippingrules_carrier_id',
        ));

        $this->addColumn('name', array(
            'header' => $this->__('Name'),
            'align'  => 'left',
            'index'  => 'name',
        ));

        $this->addColumn('is_active', array(
            'header'  => Mage::helper('adminhtml')->__('Status'),
            'align'   => 'left',
            'width'   => '80px',
            'index'   => 'is_active',
            'type'    => 'options',
            'options' => Mage::getModel('easyshippingrules/system_status')->toArray(),
        ));

        $this->addColumn('position', array(
            'header' => $this->__('Position'),
            'align'  => 'right',
            'width'  => '80px',
            'index'  => 'position',
        ));

        parent::_prepareColumns();
        
        return $this;
    }

    /**
     * Prepare grid massaction actions
     *
     * @return MatheusGontijo_EasyShippingRules_Block_Adminhtml_Easyshippingrules_Carrier_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('easyshippingrules_carrier_id');

        $this
            ->getMassactionBlock()
            ->setFormFieldName('carrier');

        $this
            ->getMassactionBlock()
            ->addItem('delete', array(
                 'label'   => Mage::helper('adminhtml')->__('Delete'),
                 'url'     => $this->getUrl('*/*/massDelete'),
                 'confirm' => Mage::helper('easyshippingrules')->__('Are you sure?')
            ));

        $this
            ->getMassactionBlock()
            ->addItem('status', array(
                'label' => Mage::helper('easyshippingrules')->__('Change Status'),
                'url'   => $this->getUrl('*/*/massStatus', array('_current' => true)),
                'additional' => array('visibility'  => array(
                    'name'   => 'status',
                    'type'   => 'select',
                    'class'  => 'required-entry',
                    'label'  => Mage::helper('adminhtml')->__('Status'),
                    'values' => Mage::getModel('easyshippingrules/system_status')->toArray(),
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
