<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * Adminhtml Rule controller
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Adminhtml_Easyshippingrules_RuleController
    extends Mage_Adminhtml_Controller_Action
{
    /**
     * Init rule
     */
    protected function _initRule()
    {
        $this->_title($this->__('Easy Shipping Rules'))->_title($this->__('Rules'));

        Mage::register('current_easyshippingrules_rule', Mage::getModel('easyshippingrules/rule'));

        $id = (int) $this->getRequest()->getParam('id');

        if (!$id && $this->getRequest()->getParam('easyshippingrules_rule_id')) {
            $id = (int) $this->getRequest()->getParam('easyshippingrules_rule_id');
        }

        if ($id) {
            Mage::registry('current_easyshippingrules_rule')->load($id);
        }
    }

    /**
     * Init action
     *
     * @return MatheusGontijo_EasyShippingRules_Adminhtml_Easyshippingrules_RuleController
     */
    protected function _initAction()
    {
        $this
            ->loadLayout()
            ->_setActiveMenu('easyshippingrules/rules')
            ->_addBreadcrumb(
                $this->__('Easy Shipping Rules'),
                $this->__('Easy Shipping Rules')
            );

        return $this;
    }

    /**
     * Rule index action
     */
    public function indexAction()
    {
        $this->_title($this->__('Easy Shipping Rules'))->_title($this->__('Rules'));

        $this
            ->_initAction()
            ->_addBreadcrumb(
                $this->__('Easy Shipping Rules'),
                $this->__('Easy Shipping Rules')
            )
            ->renderLayout();
    }

    /**
     * Rule grid action
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Rule new action
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * Rule edit action
     */
    public function editAction()
    {
        $id    = $this->getRequest()->getParam('id');
        $model = Mage::getModel('easyshippingrules/rule');

        if ($id) {
            $model->load($id);

            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(
                    $this->__('This shipping rule no longer exists.')
                );

                $this->_redirect('*/*');

                return;
            }
        }

        $this->_title($model->getId() ? $model->getName() : $this->__('New Shipping Rule'));

        $data = Mage::getSingleton('adminhtml/session')->getPageData(true);

        if (!empty($data)) {
            $model->addData($data);
        }

        $model
            ->getConditions()
            ->setJsFormObject(
                MatheusGontijo_EasyShippingRules_Block_Adminhtml_Easyshippingrules_Widget_Chooser_Rule::FORM_NAME
            );

        Mage::register('current_easyshippingrules_rule', $model);

        $this
            ->_initAction()
            ->getLayout()
            ->getBlock('admin.easyshippingrules.rule.edit')
            ->setData('action', $this->getUrl('*/*/save'));

        $this
            ->_addBreadcrumb(
                $id ? $this->__('Edit Shipping Rule')
                    : $this->__('New Shipping Rule'),
                $id ? $this->__('Edit Shipping Rule')
                    : $this->__('New Shipping Rule')
            )
            ->renderLayout();
    }

    /**
     * Rule save action
     */
    public function saveAction()
    {
        if ($this->getRequest()->getPost()) {
            try {
                /** @var $model MatheusGontijo_EasyShippingRules_Model_Rule */
                $model = Mage::getModel('easyshippingrules/rule');
                $data  = $this->getRequest()->getPost();
                $id    = $this->getRequest()->getParam('easyshippingrules_rule_id');

                if ($id) {
                    $model->load($id);

                    if ($id != $model->getId()) {
                        Mage::throwException($this->__('Wrong shipping rule specified.'));
                    }
                }

                $session = Mage::getSingleton('adminhtml/session');

                if (isset($data['rule']['conditions'])) {
                    $data['conditions'] = $data['rule']['conditions'];
                }

                unset($data['rule']);

                $model->loadPost($data);

                $session->setPageData($model->getData());

                $model->save();

                $session->addSuccess($this->__('The shipping rule has been saved.'));
                $session->setPageData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));

                    return;
                }

                $this->_redirect('*/*/');

                return;
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());

                $id = (int) $this->getRequest()->getParam('easyshippingrules_rule_id');

                if (!empty($id)) {
                    $this->_redirect('*/*/edit', array('id' => $id));
                } else {
                    $this->_redirect('*/*/new');
                }

                return;
            } catch (Exception $e) {
                $this->_getSession()->addError(
                    Mage::helper('adminhtml')->__('An error occurred while saving the shipping rule data. Please review the log and try again.')
                );

                Mage::logException($e);

                Mage::getSingleton('adminhtml/session')->setPageData($data);

                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('easyshippingrules_rule_id')));

                return;
            }
        }

        $this->_redirect('*/*/');
    }

    /**
     * Rule delete action
     */
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $model = Mage::getModel('easyshippingrules/rule');

                $model->load($id);
                $model->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    $this->__('The shipping rule has been deleted.')
                );

                $this->_redirect('*/*/');

                return;
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addError(
                    $this->__('An error occurred while deleting the shipping rule. Please review the log and try again.')
                );

                Mage::logException($e);

                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));

                return;
            }
        }

        Mage::getSingleton('adminhtml/session')->addError(
            $this->__('Unable to find a shipping rule to delete.')
        );

        $this->_redirect('*/*/');
    }

    /**
     * New condition html action
     */
    public function newConditionHtmlAction()
    {
        $id      = $this->getRequest()->getParam('id');
        $typeArr = explode('|', str_replace('-', '/', $this->getRequest()->getParam('type')));
        $type    = $typeArr[0];

        $model = Mage::getModel($type)
            ->setId($id)
            ->setType($type)
            ->setRule(Mage::getModel('easyshippingrules/rule'))
            ->setPrefix('conditions');

        if (!empty($typeArr[1])) {
            $model->setAttribute($typeArr[1]);
        }

        if ($model instanceof Mage_Rule_Model_Condition_Abstract) {
            $model->setJsFormObject($this->getRequest()->getParam('form'));
            $html = $model->asHtmlRecursive();
        } else {
            $html = '';
        }

        $this->getResponse()->setBody($html);
    }

    /**
     * Rule mass delete action
     */
    public function massDeleteAction()
    {
        $ruleIds = $this->getRequest()->getParam('rule');

        if (!is_array($ruleIds)) {
            $this->_getSession()->addError($this->__('Please select rule(s).'));
        } else {
            if (!empty($ruleIds)) {
                try {
                    foreach ($ruleIds as $ruleId) {
                        Mage::getSingleton('easyshippingrules/rule')
                            ->load($ruleId)
                            ->delete();
                    }

                    $this->_getSession()->addSuccess(
                        Mage::helper('adminhtml')->__('Total of %d record(s) have been deleted.', count($ruleIds))
                    );
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            }
        }

        $this->_redirect('*/*/index');
    }

    /**
     * Rule mass update price / percentage action
     */
    public function massUpdatePricePercentageAction()
    {
        $ruleIds         = $this->getRequest()->getParam('rule');
        $pricePercentage = (float) $this->getRequest()->getParam('price_percentage');

        if (!is_array($ruleIds)) {
            $this->_getSession()->addError($this->__('Please select rule(s).'));
        } else {
            if (!empty($ruleIds)) {
                try {
                    foreach ($ruleIds as $ruleId) {
                        Mage::getSingleton('easyshippingrules/rule')
                            ->load($ruleId)
                            ->setPricePercentage($pricePercentage)
                            ->save();
                    }

                    $this->_getSession()->addSuccess(
                        Mage::helper('adminhtml')->__('Total of %d record(s) have been deleted.', count($ruleIds))
                    );
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            }
        }

        $this->_redirect('*/*/index');
    }
}
