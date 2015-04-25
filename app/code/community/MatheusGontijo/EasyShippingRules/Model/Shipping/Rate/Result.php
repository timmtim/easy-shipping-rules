<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Model_Shipping_Rate_Result
    extends Mage_Shipping_Model_Rate_Result
{
    /**
     * Add custom methods
     *
     * @param Mage_Sales_Model_Quote $quote
     */
    public function addCustomMethods(Mage_Sales_Model_Quote $quote)
    {
        /** @var MatheusGontijo_EasyShippingRules_Model_Custommethod $methods */
        $methods = Mage::getModel('easyshippingrules/custommethod')
            ->getCollection()
            ->prepareAvailableMethods();

        $address = $quote->getShippingAddress();

        if (!$this->_isDefaultRuleValid($address)) {
            return;
        }

        foreach ($methods as $method) {
            foreach ($method->getRules() as $rule) {
                if ($rule->validate($address)) {
                    $this->_rates[] = $this->createRate($method, $rule);
                }
            }
        }
    }

    /**
     * Check if is valid default rule assigned in system config
     *
     * @param Mage_Sales_Model_Quote_Address $address
     *
     * @return bool
     */
    protected function _isDefaultRuleValid(Mage_Sales_Model_Quote_Address $address)
    {
        $ruleId = Mage::getStoreConfig('easyshippingrules/general/default_rule');

        if (!$ruleId) {
            return true;
        }

        $rule = Mage::getModel('easyshippingrules/rule')->load($ruleId);

        if (!$rule->getId()) {
            return true;
        }

        if (!$rule->validate($address)) {
            return false;
        }

        return true;
    }

    /**
     * Create a new rate
     *
     * @param MatheusGontijo_EasyShippingRules_Model_Custommethod $method
     * @param MatheusGontijo_EasyShippingRules_Model_Rule         $rule
     *
     * @return Mage_Shipping_Model_Rate_Result_Method
     */
    public function createRate(
        MatheusGontijo_EasyShippingRules_Model_Custommethod $method,
        MatheusGontijo_EasyShippingRules_Model_Rule $rule
    ) {
        /** @var Mage_Shipping_Model_Rate_Result_Method $rate */
        $rate = Mage::getModel('shipping/rate_result_method');

        $rate->setCarrier(MatheusGontijo_EasyShippingRules_Model_Shipping_Carrier::EASY_SHIPPING_RULES_CODE . '_' . $method->getEasyshippingrulesCarrierId());
        $rate->setCarrierTitle($method->getCarrierName());
        $rate->setMethod(MatheusGontijo_EasyShippingRules_Model_Shipping_Carrier::EASY_SHIPPING_RULES_CODE . '_' . $method->getId() . '_' . $rule->getId());
        $rate->setMethodTitle($method->getName());

        $price = 0;

        if ($method->getPricePercentage()) {
            $price = $method->getPricePercentage();
        } else {
            /**
             * it makes no sense apply all price conditions
             * because each iteration will replace last one.
             * In this case admin user should setting price in method, not in rules
             */
            foreach ($method->getRules() as $rule) {
                $price = $rule->getPricePercentage();
                break;
            }
        }

        if ($method->getPriceAction() == MatheusGontijo_EasyShippingRules_Helper_Data::FIXED_PER_ITEM_QTY_PRICE_ACTION) {
            $price = Mage::getSingleton('checkout/cart')->getQuote()->getItemsQty() * $price;
        } elseif ($method->getPriceAction() == MatheusGontijo_EasyShippingRules_Helper_Data::SUBTOTAL_PERCENTAGE_PRICE_ACTION) {
            $price = $this->_getSubtotal() * ($price / 100);
        }

        $rate->setPrice($price);

        return $rate;
    }

    /**
     * Apply existing methods
     */
    public function applyExistingMethods()
    {
        /** @var MatheusGontijo_EasyShippingRules_Model_Existingmethod $methods */
        $methods = Mage::getModel('easyshippingrules/existingmethod')
            ->getCollection()
            ->prepareAvailableMethods();

        $address = Mage::getSingleton('checkout/session')
            ->getQuote()
            ->getShippingAddress();

        foreach ($methods as $method) {
            foreach ($method->getRules() as $rule) {
                if ($rule->validate($address)) {
                    $this->_applyExistingMethod($method);
                }
            }
        }
    }

    /**
     * Apply existing method
     *
     * @param MatheusGontijo_EasyShippingRules_Model_Existingmethod $method
     */
    protected function _applyExistingMethod(
        MatheusGontijo_EasyShippingRules_Model_Existingmethod $method
    ) {
        $ratesForApply = $this->_ratesForApply($method->getShippingMethodCodesFormatted());

        if (!$ratesForApply) {
            return;
        }

        foreach ($ratesForApply as $rateKey => $rate) {
            /** @var Mage_Shipping_Model_Rate_Result_Method $rate */

            /**
             * Hide rate according price action
             */
            if ($method->getAction() == MatheusGontijo_EasyShippingRules_Model_Existingmethod::HIDE_ACTION) {
                unset($this->_rates[$rateKey]);
                continue;
            }

            if ($method->getName()) {
                $rate->setName($method->getName());
            }

            if ($method->getName()) {
                $rate->setName($method->getName());
            }

            $price = 0;

            if ($method->getPricePercentage()) {
                $price = $method->getPricePercentage();
            } else {
                /**
                 * it makes no sense apply all price conditions
                 * because each iteration will replace iteration before.
                 * In this case admin user should setting price in method, not in rules
                 */
                foreach ($method->getRules() as $rule) {
                    $price = $rule->getPricePercentage();
                    break;
                }
            }

            if ($method->getPriceAction() == MatheusGontijo_EasyShippingRules_Helper_Data::FIXED_PER_ITEM_QTY_PRICE_ACTION) {
                $price = Mage::getSingleton('checkout/cart')->getQuote()->getItemsQty() * $price;
            } elseif ($method->getPriceAction() == MatheusGontijo_EasyShippingRules_Helper_Data::SUBTOTAL_PERCENTAGE_PRICE_ACTION) {
                $price = $this->_getSubtotal() * ($price / 100);
            } elseif ($method->getPriceAction() == MatheusGontijo_EasyShippingRules_Helper_Data::RATE_PERCENTAGE_PRICE_ACTION) {
                $price = $rate->getPrice() * ($price / 100);
            }

            switch ($method->getAction()) {
                case MatheusGontijo_EasyShippingRules_Model_Existingmethod::OVERWRITE_ACTION:
                    $rate->setPrice($price);
                    break;

                case MatheusGontijo_EasyShippingRules_Model_Existingmethod::DISCOUNT_ACTION:
                    $discountPrice = max($rate->getPrice() - $price, 0);
                    $rate->setPrice($discountPrice);
                    break;

                case MatheusGontijo_EasyShippingRules_Model_Existingmethod::SURCHARGE_ACTION:
                    $rate->setPrice($rate->getPrice() + $price);
                    break;
            }
        }
    }

    /**
     * Select shipping method rates to apply
     *
     * @param array $shippingMethodCodes
     *
     * @return array
     */
    protected function _ratesForApply(array $shippingMethodCodes)
    {
        $rates = array();
        foreach ($this->getAllRates() as $key => $rate) {
            if (
                   in_array(MatheusGontijo_EasyShippingRules_Model_Existingmethod::ALL_SHIPPING_METHODS_OPTION, $shippingMethodCodes)
                || in_array($rate->getMethod(), $shippingMethodCodes)
            ) {
                $rates[$key] = $rate;
            }
        }

        return $rates;
    }

    /**
     * Retrieve session subtotal
     *
     * @return float
     */
    protected function _getSubtotal()
    {
        $totals = Mage::getSingleton('checkout/session')->getQuote()->getTotals();

        return $totals['subtotal']->getValue();
    }
}
