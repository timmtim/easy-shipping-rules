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
class MatheusGontijo_EasyShippingRules_Model_Shipping_Quote_Address
    extends Mage_Sales_Model_Quote_Address
{
    /**
     * Overwrite parent method to apply custom and restriction methods
     *
     * @param Mage_Sales_Model_Quote_Item_Abstract $item
     *
     * @return boolean
     */
    public function requestShippingRates(Mage_Sales_Model_Quote_Item_Abstract $item = null)
    {
        /** @var $request Mage_Shipping_Model_Rate_Request */
        $request = Mage::getModel('shipping/rate_request');

        $request->setAllItems($item ? array($item) : $this->getAllItems());
        $request->setDestCountryId($this->getCountryId());
        $request->setDestRegionId($this->getRegionId());
        $request->setDestRegionCode($this->getRegionCode());

        /**
         * need to call getStreet with -1
         * to get data in string instead of array
         */
        $request->setDestStreet($this->getStreet(-1));
        $request->setDestCity($this->getCity());
        $request->setDestPostcode($this->getPostcode());
        $request->setPackageValue($item ? $item->getBaseRowTotal() : $this->getBaseSubtotal());

        $packageValueWithDiscount = $item
            ? $item->getBaseRowTotal() - $item->getBaseDiscountAmount()
            : $this->getBaseSubtotalWithDiscount();

        $request->setPackageValueWithDiscount($packageValueWithDiscount);
        $request->setPackageWeight($item ? $item->getRowWeight() : $this->getWeight());
        $request->setPackageQty($item ? $item->getQty() : $this->getItemQty());

        /**
         * Need for shipping methods that use insurance based on price of physical products
         */
        $packagePhysicalValue = $item
            ? $item->getBaseRowTotal()
            : $this->getBaseSubtotal() - $this->getBaseVirtualAmount();

        $request->setPackagePhysicalValue($packagePhysicalValue);
        $request->setFreeMethodWeight($item ? 0 : $this->getFreeMethodWeight());

        /**
         * Store and website identifiers need specify from quote
         */
        /*$request->setStoreId(Mage::app()->getStore()->getId());
        $request->setWebsiteId(Mage::app()->getStore()->getWebsiteId());*/

        $request->setStoreId($this->getQuote()->getStore()->getId());
        $request->setWebsiteId($this->getQuote()->getStore()->getWebsiteId());
        $request->setFreeShipping($this->getFreeShipping());

        /**
         * Currencies need to convert in free shipping
         */
        $request->setBaseCurrency($this->getQuote()->getStore()->getBaseCurrency());
        $request->setPackageCurrency($this->getQuote()->getStore()->getCurrentCurrency());
        $request->setLimitCarrier($this->getLimitCarrier());

        $request->setBaseSubtotalInclTax($this->getBaseSubtotalInclTax());

        $result = Mage::getModel('shipping/shipping')->collectRates($request)->getResult();

        $result->addCustomMethods($this->getQuote());
        $result->applyExistingMethods($this->getQuote());

        $found = false;
        if ($result) {
            $shippingRates = $result->getAllRates();
            foreach ($shippingRates as $shippingRate) {
                $rate = Mage::getModel('easyshippingrules/sales_quote_address_rate')
                    ->importShippingRate($shippingRate);

                if (!$item) {
                    $this->addShippingRate($rate);
                }

                if ($this->getShippingMethod() == $rate->getCode()) {
                    if ($item) {
                        $item->setBaseShippingAmount($rate->getPrice());
                    } else {
                        /**
                         * possible bug: this should be setBaseShippingAmount(),
                         * see Mage_Sales_Model_Quote_Address_Total_Shipping::collect()
                         * where this value is set again from the current specified rate price
                         * (looks like a workaround for this bug)
                         */
                        $this->setShippingAmount($rate->getPrice());
                    }

                    $found = true;
                }
            }
        }
        
        return $found;
    }

    /**
     * Overwrite parent method to add properly sort order
     * when it starts with easyshippingrules
     *
     * @return array
     */
    public function getGroupedAllShippingRates()
    {
        $rates = array();

        foreach ($this->getShippingRatesCollection() as $rate) {
            if (!$rate->isDeleted() && $rate->getCarrierInstance()) {
                if (!isset($rates[$rate->getCarrier()])) {
                    $rates[$rate->getCarrier()] = array();
                }

                $rates[$rate->getCarrier()][] = $rate;

                if (strpos($rate->getCarrier(), MatheusGontijo_EasyShippingRules_Model_Shipping_Carrier::EASY_SHIPPING_RULES_CODE) === 0) {
                    $rates[$rate->getCarrier()][0]->carrier_sort_order = $this->_getEasyShippingRulesSortOrder($rate);
                } else {
                    $rates[$rate->getCarrier()][0]->carrier_sort_order = $rate->getCarrierInstance()->getSortOrder();
                }
            }
        }

        uasort($rates, array($this, '_sortRates'));

        return $rates;
    }

    /**
     * Retrieve carrier position based on a rate
     *
     * @param Mage_Sales_Model_Quote_Address_Rate $rate
     *
     * @return int
     */
    protected function _getEasyShippingRulesSortOrder(Mage_Sales_Model_Quote_Address_Rate $rate)
    {
        $carrierExploded = explode('_', $rate->getCarrier());
        $carrierId       = isset($carrierExploded[1]) ? $carrierExploded[1] : 0;

        return Mage::getModel('easyshippingrules/carrier')
            ->load($carrierId)
            ->getPosition();
    }
}
