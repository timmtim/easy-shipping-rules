<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * Easy Shipping Rules carrier
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Model_Shipping_Carrier
    extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{
    /**
     * Easy Shipping Rules carrier code
     */
    const EASY_SHIPPING_RULES_CODE = 'easyshippingrules';

    /**
     * Overwrite and returning false to break unnecessary functionality
     *
     * @param Mage_Shipping_Model_Rate_Request $request
     *
     * @return boolean
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        return false;
    }

    /**
     * Overwrite and returning false to break unnecessary functionality
     *
     * @return array
     */
    public function getAllowedMethods()
    {
        return array();
    }
}
