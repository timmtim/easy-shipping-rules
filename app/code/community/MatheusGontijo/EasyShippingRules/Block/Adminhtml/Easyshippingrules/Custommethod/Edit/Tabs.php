<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * Adminhtml Easy Shipping Rules Custom Methods Edit Tabs block
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Block_Adminhtml_Easyshippingrules_Custommethod_Edit_Tabs
    extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->setId('easyshippingrules_custom_method_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle($this->__('Custom Method'));
    }

    /**
     * Add tabs
     *
     * @return Mage_Core_Block_Abstract
     */
    protected function _prepareLayout()
    {
        $this->addTab('main', array(
            'label'   => $this->__('General'),
            'content' => $this->getLayout()
                ->createBlock('easyshippingrules/adminhtml_easyshippingrules_custommethod_edit_tab_main')->toHtml(),
        ));

        $this->addTab('rules', array(
            'label' => $this->__('Rules'),
            'url'   => $this->getUrl('*/*/rules', array('_current' => true)),
            'class' => 'ajax',
        ));

        $this->addTab('actions', array(
            'label'   => $this->__('Actions'),
            'content' => $this->getLayout()
                ->createBlock('easyshippingrules/adminhtml_easyshippingrules_custommethod_edit_tab_actions')->toHtml(),
        ));

        return parent::_prepareLayout();
    }
}
