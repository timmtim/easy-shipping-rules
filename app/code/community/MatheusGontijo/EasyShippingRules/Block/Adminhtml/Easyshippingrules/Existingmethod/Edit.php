<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * Adminhtml Easy Shipping Rules Existing Methods Edit block
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Block_Adminhtml_Easyshippingrules_Existingmethod_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_objectId   = 'id';
        $this->_blockGroup = 'easyshippingrules';
        $this->_controller = 'adminhtml_easyshippingrules_existingmethod';

        parent::__construct();

        $this->_addButton('save_and_continue_edit', array(
            'class'   => 'save',
            'label'   => Mage::helper('adminhtml')->__('Save and Continue Edit'),
            'onclick' => 'editForm.submit($(\'edit_form\').action + \'back/edit/\')',
        ), 10);
    }

    /**
     * Getter for form header text
     *
     * @return string
     */
    public function getHeaderText()
    {
        $method = Mage::registry('current_easyshippingrules_existing_method');

        if ($method->getId()) {
            return $this->__("Edit Existing Method '%s'", $this->escapeHtml($method->getName()));
        } else {
            return $this->__('New Existing Method');
        }
    }

    /**
     * Get form action URL
     *
     * @return string
     */
    public function getFormActionUrl()
    {
        return $this->getUrl('*/' . $this->_blockGroup . '_existingmethod/save');
    }
}
