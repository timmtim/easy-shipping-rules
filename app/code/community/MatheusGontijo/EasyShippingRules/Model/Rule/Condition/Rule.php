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
class MatheusGontijo_EasyShippingRules_Model_Rule_Condition_Rule
    extends Mage_Rule_Model_Condition_Abstract
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->setType('easyshippingrules/rule_condition_rule');
    }

    /**
     * Load attribute options
     *
     * @return MatheusGontijo_EasyShippingRules_Model_Rule_Condition_Rule
     */
    public function loadAttributeOptions()
    {
        $this->setAttributeOption(array(
            'rule' => Mage::helper('easyshippingrules')->__('Rule'),
        ));

        return $this;
    }

    /**
     * Retrieve input type
     *
     * @return MatheusGontijo_EasyShippingRules_Model_Rule_Condition_Rule
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
     * Retrieve after element HTML
     *
     * @return string
     */
    public function getValueAfterElementHtml()
    {
        $html = '';

        $image = Mage::getDesign()->getSkinUrl('images/rule_chooser_trigger.gif');

        if (!empty($image)) {
            $html = '<a href="javascript:void(0)" class="rule-chooser-trigger"><img src="' . $image . '" alt="" class="v-middle rule-chooser-trigger" title="' . Mage::helper('rule')->__('Open Chooser') . '" /></a>';
        }

        return $html;
    }

    /**
     * Retrieve value element chooser URL
     *
     * @return string
     */
    public function getValueElementChooserUrl()
    {
        return Mage::helper('adminhtml')->getUrl('adminhtml/easyshippingrules_widget/chooser');
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
        $value   = $this->getValueParsed();
        $ruleIds = explode(',', $value);

        if (!$ruleIds) {
            return false;
        }

        foreach ($ruleIds as $ruleId) {
            $rule = Mage::getModel('easyshippingrules/rule')->load($ruleId);

            if (!$rule->getId()) {
                return false;
            }

            $result = $rule->validate($object);

            if (
                   ($this->getOperator() == '==' && !$result)
                || ($this->getOperator() == '!=' && $result)
            ) {
                return false;
            }
        }

        return true;
    }
}
