<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * Adminhtml Easy Shipping Rules Custom Methods Grid block
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Block_Adminhtml_Easyshippingrules_Custommethod_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->setId('easyshippingrules_custommethod_grid');
        $this->setUseAjax(true);
        $this->setDefaultSort('easyshippingrules_custom_method_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Prepare grid collection object
     *
     * @return MatheusGontijo_EasyShippingRules_Block_Adminhtml_Easyshippingrules_Custommethod_Grid
     */
    protected function _prepareCollection()
    {
        /** @var $collection MatheusGontijo_EasyShippingRules_Model_Mysql4_Custommethod_Collection */
        $collection = Mage::getModel('easyshippingrules/custommethod')
            ->getResourceCollection();

        $this->setCollection($collection);

        parent::_prepareCollection();

        return $this;
    }

    /**
     * Add grid columns
     *
     * @return MatheusGontijo_EasyShippingRules_Block_Adminhtml_Easyshippingrules_Custommethod_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('easyshippingrules_custom_method_id', array(
            'header' => $this->__('ID'),
            'align'  => 'right',
            'width'  => '50px',
            'type'   => 'number',
            'index'  => 'easyshippingrules_custom_method_id',
        ));

        $this->addColumn('name', array(
            'header' => $this->__('Name'),
            'align'  => 'left',
            'index'  => 'name',
        ));

        $this->addColumn('carrier_name', array(
            'header'  => $this->__('Carrier'),
            'align'   => 'left',
            'width'   => '350px',
            'index'   => 'carrier_name',
            'filter_index' => 'c.easyshippingrules_carrier_id',
            'type'    => 'options',
            'options' => Mage::getResourceSingleton('easyshippingrules/carrier_collection')->toOptionHash(),
        ));

        $this->addColumn('is_active', array(
            'header'  => Mage::helper('adminhtml')->__('Status'),
            'align'   => 'left',
            'width'   => '80px',
            'index'   => 'is_active',
            'type'    => 'options',
            'options' => Mage::getModel('easyshippingrules/system_status')->toArray(),
        ));

        $this->addColumn('price_action', array(
            'header'  => $this->__('Price Action'),
            'align'   => 'left',
            'width'   => '150px',
            'index'   => 'price_action',
            'type'    => 'options',
            'options' => Mage::getModel('easyshippingrules/system_priceaction')->toArray(true),
        ));

        $this->addColumn('price_percentage', array(
            'header' => $this->__('Price / Percentage'),
            'align'  => 'right',
            'width'  => '80px',
            'type'   => 'currency',
            'currency_code' => Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
            'index'  => 'price_percentage',
        ));

        $this->addColumn('created_at', array(
            'header' => $this->__('Created At'),
            'type'   => 'datetime',
            'width'  => '150px',
            'index'  => 'created_at',
        ));

        $this->addColumn('updated_at', array(
            'header' => $this->__('Updated At'),
            'type'   => 'datetime',
            'width'  => '150px',
            'index'  => 'updated_at',
        ));

        parent::_prepareColumns();
        
        return $this;
    }

    /**
     * Prepare grid massaction actions
     *
     * @return MatheusGontijo_EasyShippingRules_Block_Adminhtml_Easyshippingrules_Custommethod_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('easyshippingrules_custommethod_id');

        $this
            ->getMassactionBlock()
            ->setFormFieldName('custom_method');

        $this
            ->getMassactionBlock()
            ->addItem('delete', array(
                 'label'   => Mage::helper('adminhtml')->__('Delete'),
                 'url'     => $this->getUrl('*/*/massDelete'),
                 'confirm' => Mage::helper('easyshippingrules')->__('Are you sure?')
            ));

        $carrierValues = Mage::getResourceSingleton('easyshippingrules/carrier_collection')->toOptionArray();
        array_unshift($carrierValues, array('label' => '', 'value' => ''));

        $this
            ->getMassactionBlock()
            ->addItem('carrier', array(
                'label' => Mage::helper('easyshippingrules')->__('Update Carrier'),
                'url'   => $this->getUrl('*/*/massUpdateCarrier', array('_current' => true)),
                'additional' => array('visibility'  => array(
                    'name'   => 'carrier',
                    'type'   => 'select',
                    'label'  => Mage::helper('easyshippingrules')->__('Carrier'),
                    'values' => $carrierValues,
                ),
            )));

        $this
            ->getMassactionBlock()
            ->addItem('status', array(
                'label' => Mage::helper('easyshippingrules')->__('Update Status'),
                'url'   => $this->getUrl('*/*/massUpdateStatus', array('_current' => true)),
                'additional' => array('visibility'  => array(
                    'name'   => 'status',
                    'type'   => 'select',
                    'class'  => 'required-entry',
                    'label'  => Mage::helper('adminhtml')->__('Status'),
                    'values' => Mage::getModel('easyshippingrules/system_status')->toArray(),
                    'value'  => 1,
                ),
            )));

        $this
            ->getMassactionBlock()
            ->addItem('price_action', array(
                'label' => Mage::helper('easyshippingrules')->__('Update Price Action'),
                'url'   => $this->getUrl('*/*/massUpdatePriceAction', array('_current' => true)),
                'additional' => array('visibility'  => array(
                    'name'   => 'price_action',
                    'type'   => 'select',
                    'class'  => 'required-entry',
                    'label'  => Mage::helper('adminhtml')->__('Price Action'),
                    'values' => Mage::getModel('easyshippingrules/system_priceaction')->toArray(),
                ),
            )));

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
