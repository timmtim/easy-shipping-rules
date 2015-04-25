<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * Easy Shipping Rules Checkout Cart block
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Block_Checkout_Cart_Shipping
    extends Mage_Checkout_Block_Cart_Shipping
{
    /**
     * Overwrite parent method to adding properly carrier name
     * when it starts with assigned $carrierCode ("easyshippingrules")
     *
     * @param string $carrierCode
     *
     * @return string
     */
    public function getCarrierName($carrierCode)
    {
        $carrierName = Mage::helper('easyshippingrules')
            ->getCarrierName($carrierCode);

        if ($carrierName) {
            return $carrierName;
        }

        return parent::getCarrierName($carrierCode);
    }
}
