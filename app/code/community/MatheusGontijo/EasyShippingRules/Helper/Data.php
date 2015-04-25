<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * Data helper
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Helper_Data
    extends Mage_Core_Helper_Abstract
{
    /**
     * Price actions to apply
     */
    const FIXED_ALL_CART_PRICE_ACTION      = 'fixed_all_cart';
    const FIXED_PER_ITEM_QTY_PRICE_ACTION  = 'fixed_per_item_qty';
    const SUBTOTAL_PERCENTAGE_PRICE_ACTION = 'percentage';
    const RATE_PERCENTAGE_PRICE_ACTION     = 'percentage_rate';

    /**
     * Methods are being replaced and here is centralized business rules
     *
     * @param string $carrierCode
     *
     * @return string
     */
    public function getCarrierName($carrierCode)
    {
        if (strpos($carrierCode, MatheusGontijo_EasyShippingRules_Model_Shipping_Carrier::EASY_SHIPPING_RULES_CODE) === 0) {
            $carrierExploded = explode('_', $carrierCode);
            $carrierId       = isset($carrierExploded[1]) ? $carrierExploded[1] : 0;

            return Mage::getModel('easyshippingrules/carrier')
                ->load($carrierId)
                ->getName();
        }

        return false;
    }
}
