<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * Adminhtml Easy Shipping Rules Carrier Edit Tabs block
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Block_Adminhtml_Easyshippingrules_Carrier_Edit_Tabs
    extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->setId('easyshippingrules_carrier_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle($this->__('Carrier'));
    }
}
