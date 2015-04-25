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
class MatheusGontijo_EasyShippingRules_Model_Rule_Condition_Product
    extends Mage_SalesRule_Model_Rule_Condition_Product
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->setType('easyshippingrules/rule_condition_product');
    }

    /**
     * Overwrite parent method to add new operators
     *
     * @param array $arr
     *
     * @return MatheusGontijo_EasyShippingRules_Model_Rule_Condition_Product|Mage_CatalogRule_Model_Rule_Condition_Product
     */
    public function loadArray($arr)
    {
        $isBetweenOperator = isset($arr['operator']) && in_array($arr['operator'], array('><', '<>'));

        if (isset($arr['value']) && $isBetweenOperator) {
            $valueExploded = explode('-', $arr['value']);

            if (isset($valueExploded[0]) && isset($valueExploded[1])) {
                $arr['value'] =
                    Mage::app()->getLocale()->getNumber($valueExploded[0])
                    . '-' .
                    Mage::app()->getLocale()->getNumber($valueExploded[1]);

                return Mage_Rule_Model_Condition_Abstract::loadArray($arr);
            } else {
                $arr['value'] = '';
            }
        }

        return parent::loadArray($arr);
    }

    /**
     * Overwrite parent method to add new operators
     *
     * @return array
     */
    public function getDefaultOperatorOptions()
    {
        if ($this->_defaultOperatorOptions === null) {
            $options = parent::getDefaultOperatorOptions();

            $options['><'] = Mage::helper('easyshippingrules')->__('between');
            $options['<>'] = Mage::helper('easyshippingrules')->__('not between');

            $this->_defaultOperatorOptions = $options;
        }

        return $this->_defaultOperatorOptions;
    }

    /**
     * Overwrite parent method to add new operators
     *
     * @return array
     */
    public function getDefaultOperatorInputByType()
    {
        if ($this->_defaultOperatorInputByType === null) {
            $defaultOperatorInputByType = parent:: getDefaultOperatorInputByType();

            $types = array(
                'string',
                'numeric',
            );

            foreach ($types as $type) {
                if (isset($defaultOperatorInputByType[$type])) {
                    $defaultOperatorInputByType[$type][] = '><';
                    $defaultOperatorInputByType[$type][] = '<>';
                }
            }

            $this->_defaultOperatorInputByType = $defaultOperatorInputByType;
        }

        return $this->_defaultOperatorInputByType;
    }

    /**
     * Overwrite parent method to add new operators
     *
     * @param   mixed $validatedValue
     *
     * @return  bool
     */
    public function validateAttribute($validatedValue)
    {
        if (is_object($validatedValue)) {
            return false;
        }

        /**
         * Condition attribute value
         */
        $value = $this->getValueParsed();

        /**
         * Comparison operator
         */
        $op = $this->getOperatorForValidate();

        // if operator requires array and it is not, or on opposite, return false
        if ($this->isArrayOperatorType() xor is_array($value)) {
            return false;
        }

        switch ($op) {
            case '><':
            case '<>':
                $explodedValue = explode('-', $value);

                $result =
                       $validatedValue >= $explodedValue[0]
                    && $validatedValue <= $explodedValue[1];
                break;

            default:
                return parent::validateAttribute($validatedValue);
                break;
        }

        if ($op == '<>') {
            $result = !$result;
        }

        return $result;
    }
}
