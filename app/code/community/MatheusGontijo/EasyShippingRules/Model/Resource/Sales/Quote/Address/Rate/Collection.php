<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * Sales Quote Address Rate collection
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Model_Resource_Sales_Quote_Address_Rate_Collection
    extends Mage_Sales_Model_Resource_Quote_Address_Rate_Collection
{
    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init('easyshippingrules/sales_quote_address_rate');
    }
}
