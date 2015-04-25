<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * Adminhtml Easy Shipping Rules Rule Edit Tab Conditions block
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Block_Adminhtml_Easyshippingrules_Rule_Edit_Tab_Conditions
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Prepare conditions form
     *
     * @return MatheusGontijo_EasyShippingRules_Block_Adminhtml_Easyshippingrules_Rule_Edit_Tab_Conditions
     */
    protected function _prepareForm()
    {
        $model = Mage::registry('current_easyshippingrules_rule');

        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('rule_');

        $renderer = Mage::getBlockSingleton('adminhtml/widget_form_renderer_fieldset')
            ->setTemplate('promo/fieldset.phtml')
            ->setNewChildUrl(
                $this->getUrl(
                    '*/easyshippingrules_rule/newConditionHtml/form/'
                    . MatheusGontijo_EasyShippingRules_Block_Adminhtml_Easyshippingrules_Widget_Chooser_Rule::FORM_NAME
                )
            );

        $fieldset = $form
            ->addFieldset('conditions_fieldset', array(
                'legend' => $this->__('Apply the shipping rule only if the following conditions are met (leave blank for all products)'),
            ))
            ->setRenderer($renderer);

        $fieldset
            ->addField('conditions', 'text', array(
                'name'  => 'conditions',
                'label' => $this->__('Conditions'),
                'title' => $this->__('Conditions'),
            ))
            ->setRule($model)
            ->setRenderer(Mage::getBlockSingleton('rule/conditions'));

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
        return $this->__('Conditions');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->__('Conditions');
    }

    /**
     * Returns status flag about this tab can be showen or not
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
