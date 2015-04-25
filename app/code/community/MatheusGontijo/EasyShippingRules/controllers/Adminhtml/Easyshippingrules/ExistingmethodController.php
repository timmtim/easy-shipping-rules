<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * Adminhtml Existing Method controller
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Adminhtml_Easyshippingrules_ExistingmethodController
    extends Mage_Adminhtml_Controller_Action
{
    /**
     * Init action
     */
    protected function _initAction()
    {
        $this
            ->loadLayout()
            ->_setActiveMenu('easyshippingrules/existingmethod')
            ->_addBreadcrumb(
                $this->__('Easy Shipping Rules'),
                $this->__('Easy Shipping Rules')
            );

        return $this;
    }

    /**
     * Existing Method index action
     */
    public function indexAction()
    {
        $this->_title($this->__('Easy Shipping Rules'))->_title($this->__('Existing Methods'));

        $this
            ->_initAction()
            ->_addBreadcrumb(
                $this->__('Easy Shipping Rules'),
                $this->__('Easy Shipping Rules')
            )
            ->renderLayout();
    }

    /**
     * Existing method grid action
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Existing Method new action
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * Existing Method edit action
     */
    public function editAction()
    {
        $id    = $this->getRequest()->getParam('id');
        $model = Mage::getModel('easyshippingrules/existingmethod');

        if ($id) {
            $model->load($id);

            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(
                    $this->__('This existing method no longer exists.')
                );

                $this->_redirect('*/*');

                return;
            }
        }

        $this->_title($model->getId() ? $model->getExistingMethodName() : $this->__('New Existing Method'));

        $data = Mage::getSingleton('adminhtml/session')->getPageData(true);

        if (!empty($data)) {
            $model->addData($data);
        }

        Mage::register('current_easyshippingrules_existing_method', $model);

        $this
            ->_initAction()
            ->getLayout()
            ->getBlock('admin.easyshippingrules.existingmethod.edit')
            ->setData('action', $this->getUrl('*/*/save'));

        $this
            ->_addBreadcrumb(
                $id ? $this->__('Edit Existing Method')
                    : $this->__('New Existing Method'),
                $id ? $this->__('Edit Existing Method')
                    : $this->__('New Existing Method')
            )
            ->renderLayout();

    }

    /**
     * Existing Method save action
     */
    public function saveAction()
    {
        if ($this->getRequest()->getPost()) {
            try {
                /** @var $model MatheusGontijo_EasyShippingRules_Model_Existingmethod */
                $model = Mage::getModel('easyshippingrules/existingmethod');
                $data  = $this->getRequest()->getPost('existing_method');
                $id    = isset($data['easyshippingrules_existing_method_id']) ? $data['easyshippingrules_existing_method_id'] : null;

                if ($id) {
                    $model->load($id);

                    if ($id != $model->getId()) {
                        Mage::throwException($this->__('Wrong existing method specified.'));
                    }
                }

                $model->setData($data);

                $session = Mage::getSingleton('adminhtml/session');

                $session->setPageData($model->getData());

                $model->save();

                $session->addSuccess($this->__('The existing method has been saved.'));
                $session->setPageData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));

                    return;
                }

                $this->_redirect('*/*/');

                return;
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());

                if (!empty($id)) {
                    $this->_redirect('*/*/edit', array('id' => $id));
                } else {
                    $this->_redirect('*/*/new');
                }

                return;
            } catch (Exception $e) {
                $this->_getSession()->addError(
                    $this->__('An error occurred while saving the existing method data. Please review the log and try again.')
                );

                Mage::logException($e);

                Mage::getSingleton('adminhtml/session')->setPageData($data);

                $this->_redirect('*/*/edit', array('id' => $id));

                return;
            }
        }

        $this->_redirect('*/*/');
    }

    /**
     * Existing Method delete action
     */
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $model = Mage::getModel('easyshippingrules/existingmethod');
                
                $model->load($id);
                $model->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    $this->__('The existing method has been deleted.')
                );

                $this->_redirect('*/*/');

                return;
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addError(
                    $this->__('An error occurred while deleting the existing method. Please review the log and try again.')
                );

                Mage::logException($e);

                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));

                return;
            }
        }

        Mage::getSingleton('adminhtml/session')->addError(
            $this->__('Unable to find a existing method to delete.')
        );

        $this->_redirect('*/*/');
    }

    /**
     * Get rule grid and serializer block
     */
    public function rulesAction()
    {
        $id = $this->getRequest()->getParam('id');

        $model = Mage::getModel('easyshippingrules/existingmethod');
        $model->load($id);

        Mage::register('current_easyshippingrules_existing_method', $model);

        $this->loadLayout();

        $this
            ->getLayout()
            ->getBlock('rules_grid_serializer')
            ->setRules($this->getRequest()->getPost('existingmethod_rules', null));

        $this->renderLayout();
    }

    /**
     * Get rule grid
     */
    public function rulesGridAction()
    {
        $id = $this->getRequest()->getParam('id');

        $model = Mage::getModel('easyshippingrules/existingmethod');
        $model->load($id);

        Mage::register('current_easyshippingrules_existing_method', $model);

        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Existing Method mass delete action
     */
    public function massDeleteAction()
    {
        $existingmethodIds = $this->getRequest()->getParam('existing_method');

        if (!is_array($existingmethodIds)) {
            $this->_getSession()->addError($this->__('Please select existing method(s).'));
        } else {
            if (!empty($existingmethodIds)) {
                try {
                    foreach ($existingmethodIds as $existingmethodId) {
                        Mage::getSingleton('easyshippingrules/existingmethod')
                            ->load($existingmethodId)
                            ->delete();
                    }

                    $this->_getSession()->addSuccess(
                        Mage::helper('adminhtml')->__('Total of %d record(s) have been deleted.', count($existingmethodIds))
                    );
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            }
        }

        $this->_redirect('*/*/index');
    }

    /**
     * Existing Method mass update status action
     */
    public function massUpdateStatusAction()
    {
        $existingmethodIds = $this->getRequest()->getParam('existing_method');
        $status            = (int) $this->getRequest()->getParam('status');

        if (!is_array($existingmethodIds)) {
            $this->_getSession()->addError($this->__('Please select existing method(s).'));
        } else {
            if (!empty($existingmethodIds)) {
                try {
                    foreach ($existingmethodIds as $existingmethodId) {
                        Mage::getSingleton('easyshippingrules/existingmethod')
                            ->load($existingmethodId)
                            ->setIsActive($status)
                            ->save();
                    }

                    $this->_getSession()->addSuccess(
                        Mage::helper('adminhtml')->__('Total of %d record(s) have been deleted.', count($existingmethodIds))
                    );
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            }
        }

        $this->_redirect('*/*/index');
    }

    /**
     * Existing Method mass update action action
     */
    public function massUpdateActionAction()
    {
        $existingmethodIds = $this->getRequest()->getParam('existing_method');
        $action            = $this->getRequest()->getParam('action');

        if (!is_array($existingmethodIds)) {
            $this->_getSession()->addError($this->__('Please select existing method(s).'));
        } else {
            if (!empty($existingmethodIds)) {
                try {
                    foreach ($existingmethodIds as $existingmethodId) {
                        Mage::getSingleton('easyshippingrules/existingmethod')
                            ->load($existingmethodId)
                            ->setAction($action)
                            ->save();
                    }

                    $this->_getSession()->addSuccess(
                        Mage::helper('adminhtml')->__('Total of %d record(s) have been deleted.', count($existingmethodIds))
                    );
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            }
        }

        $this->_redirect('*/*/index');
    }

    /**
     * Existing Method mass update price action action
     */
    public function massUpdatePriceActionAction()
    {
        $existingmethodIds = $this->getRequest()->getParam('existing_method');
        $priceAction       = $this->getRequest()->getParam('price_action');

        if (!is_array($existingmethodIds)) {
            $this->_getSession()->addError($this->__('Please select existing method(s).'));
        } else {
            if (!empty($existingmethodIds)) {
                try {
                    foreach ($existingmethodIds as $existingmethodId) {
                        Mage::getSingleton('easyshippingrules/existingmethod')
                            ->load($existingmethodId)
                            ->setPriceAction($priceAction)
                            ->save();
                    }

                    $this->_getSession()->addSuccess(
                        Mage::helper('adminhtml')->__('Total of %d record(s) have been deleted.', count($existingmethodIds))
                    );
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            }
        }

        $this->_redirect('*/*/index');
    }

    /**
     * Existing Method mass update price / percentage action
     */
    public function massUpdatePricePercentageAction()
    {
        $existingmethodIds = $this->getRequest()->getParam('existing_method');
        $pricePercentage   = (float) $this->getRequest()->getParam('price_percentage');

        if (!is_array($existingmethodIds)) {
            $this->_getSession()->addError($this->__('Please select existing method(s).'));
        } else {
            if (!empty($existingmethodIds)) {
                try {
                    foreach ($existingmethodIds as $existingmethodId) {
                        Mage::getSingleton('easyshippingrules/existingmethod')
                            ->load($existingmethodId)
                            ->setPricePercentage($pricePercentage)
                            ->save();
                    }

                    $this->_getSession()->addSuccess(
                        Mage::helper('adminhtml')->__('Total of %d record(s) have been deleted.', count($existingmethodIds))
                    );
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            }
        }

        $this->_redirect('*/*/index');
    }
}
