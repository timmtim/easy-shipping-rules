<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 */
class MatheusGontijo_EasyShippingRules_Model_Sales_Quote_Address_Rate
    extends Mage_Sales_Model_Quote_Address_Rate
{
    /**
     * Overwrite parent method to check codes based on easyshippingmethod
     * and retrieving properly carrier
     *
     * @return Mage_Shipping_Model_Carrier_Abstract
     */
    public function getCarrierInstance()
    {
        $code = $this->getCarrier();

        // checking if code starts with easyshippingrules code
        if (
               !isset(self::$_instances[$code])
            && strpos($code, MatheusGontijo_EasyShippingRules_Model_Shipping_Carrier::EASY_SHIPPING_RULES_CODE) === 0
        ) {
            self::$_instances[$code] = Mage::getModel('easyshippingrules/shipping_carrier');
        }

        return parent::getCarrierInstance();
    }
}
