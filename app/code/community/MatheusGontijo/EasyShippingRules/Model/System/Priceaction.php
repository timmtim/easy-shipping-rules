<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * System Price Action model
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Model_System_Priceaction
{
    /**
     * Retrieve price action values for form
     *
     * @return array
     */
    public function toArray($isGrid = false, $hasRatePercentage = false)
    {
        if ($isGrid) {
            $data = array(
                MatheusGontijo_EasyShippingRules_Helper_Data::FIXED_ALL_CART_PRICE_ACTION      => Mage::helper('easyshippingrules')->__('Fixed - all cart'),
                MatheusGontijo_EasyShippingRules_Helper_Data::FIXED_PER_ITEM_QTY_PRICE_ACTION  => Mage::helper('easyshippingrules')->__('Fixed - per item qty'),
                MatheusGontijo_EasyShippingRules_Helper_Data::SUBTOTAL_PERCENTAGE_PRICE_ACTION => Mage::helper('easyshippingrules')->__('Percentage'),
            );
        } else {
            $data = array(
                MatheusGontijo_EasyShippingRules_Helper_Data::FIXED_ALL_CART_PRICE_ACTION      => Mage::helper('easyshippingrules')->__('Fixed (all cart)'),
                MatheusGontijo_EasyShippingRules_Helper_Data::FIXED_PER_ITEM_QTY_PRICE_ACTION  => Mage::helper('easyshippingrules')->__('Fixed (per item qty)'),
                MatheusGontijo_EasyShippingRules_Helper_Data::SUBTOTAL_PERCENTAGE_PRICE_ACTION => Mage::helper('easyshippingrules')->__('Percentage (based on subtotal)'),
            );
        }

        if ($hasRatePercentage) {
            $data[MatheusGontijo_EasyShippingRules_Helper_Data::RATE_PERCENTAGE_PRICE_ACTION]  =  Mage::helper('easyshippingrules')->__('Percentage (based on rate)');
        }

        return $data;
    }
}
