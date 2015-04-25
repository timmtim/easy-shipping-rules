<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * Adminhtml Easy Shipping Rules Carrier Edit Tab Main block
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Block_Adminhtml_Easyshippingrules_Carrier_Edit_Tab_Main
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Prepare main form
     *
     * @return MatheusGontijo_EasyShippingRules_Block_Adminhtml_Easyshippingrules_Carrier_Edit_Tab_Main
     */
    protected function _prepareForm()
    {
        $model = Mage::registry('current_easyshippingrules_carrier');

        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('base_fieldset',
            array('legend' => $this->__('General Information'))
        );

        if ($model->getId()) {
            $fieldset->addField('easyshippingrules_carrier_id', 'hidden', array(
                'name' => 'easyshippingrules_carrier_id',
            ));
        }

        $fieldset->addField('name', 'text', array(
            'name'     => 'name',
            'label'    => $this->__('Name'),
            'title'    => $this->__('Name'),
            'required' => true,
        ));

        $fieldset->addField('is_active', 'select', array(
            'label'    => Mage::helper('adminhtml')->__('Status'),
            'title'    => Mage::helper('adminhtml')->__('Status'),
            'name'     => 'is_active',
            'required' => true,
            'options'  => Mage::getModel('easyshippingrules/system_status')->toArray(),
        ));

        if (!$model->getId()) {
            $model->setData('is_active', 1);
        }

        $fieldset->addField('position', 'text', array(
            'name' => 'position',
            'label' => $this->__('Position'),
        ));

        $form->setValues($model->getData());

        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare content for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->__('General');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->__('General');
    }

    /**
     * Returns status flag about this tab can be showed or not
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }
}
