<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * System Shipping model
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Model_System_Shipping
{
    /**
     * Retrieve shipping values for form
     *
     * @return array
     */
    public function getShippingValuesForForm()
    {
        $options = array();

        $options[] = array(
            'label' => Mage::helper('easyshippingrules')->__('All Shipping Methods'),
            'value' => MatheusGontijo_EasyShippingRules_Model_Existingmethod::ALL_SHIPPING_METHODS_OPTION,
        );

        $carriers = Mage::getSingleton('shipping/config')->getActiveCarriers();

        foreach ($carriers as $carrierCode => $carrier) {
            $allowedMethods = $carrier->getAllowedMethods();

            if (is_array($allowedMethods) && !empty($allowedMethods)) {
                $values = array();

                foreach ($carrier->getAllowedMethods() as $methodCode => $method) {
                    $values[] = array(
                        'label' => $method,
                        'value' => $methodCode,
                    );
                }

                $options[] = array(
                    'label' => Mage::getStoreConfig('carriers/' . $carrierCode . '/title'),
                    'value' => $values,
                );
            }
        }

        return $options;
    }
}
