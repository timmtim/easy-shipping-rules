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
class MatheusGontijo_EasyShippingRules_Model_Rule_Condition_Programmaticmethod
    extends Mage_Rule_Model_Condition_Abstract
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->setType('easyshippingrules/rule_condition_programmaticmethod');
    }

    /**
     * Load attribute options
     *
     * @return MatheusGontijo_EasyShippingRules_Model_Rule_Condition_Programmaticmethod
     */
    public function loadAttributeOptions()
    {
        $this->setAttributeOption(array(
            'custommethod' => Mage::helper('easyshippingrules')->__('Programmatic Method'),
        ));

        return $this;
    }

    /**
     * Retrieve input type
     *
     * @return MatheusGontijo_EasyShippingRules_Model_Rule_Condition_Programmaticmethod
     */
    public function getInputType()
    {
        return 'boolean';
    }

    /**
     * Set attribute to show as text
     *
     * @return mixed
     */
    public function getAttributeElement()
    {
        $element = parent::getAttributeElement();

        $element->setShowAsText(true);

        return $element;
    }

    /**
     * Retrieve Explicit Apply
     *
     * @return bool
     */
    public function getExplicitApply()
    {
        return true;
    }

    /**
     * Overwrite parent method to update label
     *
     * @return array
     */
    public function getDefaultOperatorOptions()
    {
        if (!$this->_defaultOperatorOptions) {
            $defaultOperatorOptions = parent::getDefaultOperatorOptions();

            if (isset($defaultOperatorOptions['=='])) {
                $defaultOperatorOptions['=='] = Mage::helper('easyshippingrules')->__('is valid');
            }

            if (isset($defaultOperatorOptions['!='])) {
                $defaultOperatorOptions['!='] = Mage::helper('easyshippingrules')->__('is not valid');
            }

            $this->_defaultOperatorOptions = $defaultOperatorOptions;
        }

        return $this->_defaultOperatorOptions;
    }

    /**
     * Validate
     *
     * @param Varien_Object $object
     *
     * @return bool
     */
    public function validate(Varien_Object $object)
    {
        $value = explode(':', $this->getValueParsed());

        $factory = isset($value[0]) ? $value[0] : null;
        $method  = isset($value[1]) ? $value[1] : null;

        if (!$factory || !$method) {
            return false;
        }

        $model = Mage::getModel($factory);

        if (!$model || !method_exists($model, $method)) {
            return false;
        }

        try {
            if ($model->$method()) {
                $result = true;
            } else {
                $result = false;
            }
        } catch (Exception $e) {
            Mage::log($e->getMessage());
            return false;
        }

        if (
               ($this->getOperator() == '==' && !$result)
            || ($this->getOperator() == '!=' && $result)
        ) {
            return false;
        }

        return true;
    }
}
