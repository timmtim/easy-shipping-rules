<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * Adminhtml Widget controller
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Adminhtml_Easyshippingrules_WidgetController
    extends Mage_Adminhtml_Controller_Action
{
    /**
     * Prepare block for chooser
     */
    public function chooserAction()
    {
        $block = $this->getLayout()->createBlock(
            'easyshippingrules/adminhtml_easyshippingrules_widget_chooser_rule',
            'adminhtml_easyshippingrules_widget_chooser_rule',
            array('js_form_object' => MatheusGontijo_EasyShippingRules_Block_Adminhtml_Easyshippingrules_Widget_Chooser_Rule::FORM_NAME)
        );

        $this
            ->getResponse()
            ->setBody($block->toHtml());
    }
}
