<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * Adminhtml Custom Method controller
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Adminhtml_Easyshippingrules_CustommethodController
    extends Mage_Adminhtml_Controller_Action
{
    /**
     * Init action
     */
    protected function _initAction()
    {
        $this
            ->loadLayout()
            ->_setActiveMenu('easyshippingrules/custommethod')
            ->_addBreadcrumb(
                $this->__('Easy Shipping Rules'),
                $this->__('Easy Shipping Rules')
            );

        return $this;
    }

    /**
     * Custom Method index action
     */
    public function indexAction()
    {
        $this->_title($this->__('Easy Shipping Rules'))->_title($this->__('Custom Methods'));

        $this
            ->_initAction()
            ->_addBreadcrumb(
                $this->__('Easy Shipping Rules'),
                $this->__('Easy Shipping Rules')
            )
            ->renderLayout();
    }

    /**
     * Customer Method grid action
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Custom Method new action
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * Custom Method edit action
     */
    public function editAction()
    {
        $id    = $this->getRequest()->getParam('id');
        $model = Mage::getModel('easyshippingrules/custommethod');

        if ($id) {
            $model->load($id);

            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(
                    $this->__('This custom method no longer exists.')
                );

                $this->_redirect('*/*');

                return;
            }
        }

        $this->_title($model->getId() ? $model->getName() : $this->__('New Custom Method'));

        $data = Mage::getSingleton('adminhtml/session')->getPageData(true);

        if (!empty($data)) {
            $model->addData($data);
        }

        Mage::register('current_easyshippingrules_custom_method', $model);

        $this
            ->_initAction()
            ->getLayout()
            ->getBlock('admin.easyshippingrules.custommethod.edit')
            ->setData('action', $this->getUrl('*/*/save'));

        $this
            ->_addBreadcrumb(
                $id ? $this->__('Edit Custom Method')
                    : $this->__('New Custom Method'),
                $id ? $this->__('Edit Custom Method')
                    : $this->__('New Custom Method')
            )
            ->renderLayout();
    }

    /**
     * Custom Method save action
     */
    public function saveAction()
    {
        if ($this->getRequest()->getPost()) {
            try {
                /** @var $model MatheusGontijo_EasyShippingRules_Model_Custommethod */
                $model = Mage::getModel('easyshippingrules/custommethod');
                $data  = $this->getRequest()->getPost('custom_method');
                $id    = isset($data['easyshippingrules_custom_method_id']) ? $data['easyshippingrules_custom_method_id'] : null;

                if ($id) {
                    $model->load($id);

                    if ($id != $model->getId()) {
                        Mage::throwException($this->__('Wrong custom method specified.'));
                    }
                }

                $model->setData($data);

                $session = Mage::getSingleton('adminhtml/session');

                $session->setPageData($model->getData());

                $model->save();

                $session->addSuccess($this->__('The custom method has been saved.'));
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
                    $this->__(
                        'An error occurred while saving the custom method data. Please review the log and try again.'
                    )
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
     * Custom Method delete action
     */
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $model = Mage::getModel('easyshippingrules/custommethod');

                $model->load($id);
                $model->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    $this->__('The custom method has been deleted.')
                );

                $this->_redirect('*/*/');

                return;
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addError(
                    $this->__('An error occurred while deleting the custom method. Please review the log and try again.')
                );

                Mage::logException($e);

                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));

                return;
            }
        }

        Mage::getSingleton('adminhtml/session')->addError(
            $this->__('Unable to find a custom method to delete.')
        );

        $this->_redirect('*/*/');
    }

    /**
     * Get rules grid and serializer block
     */
    public function rulesAction()
    {
        $id = $this->getRequest()->getParam('id');

        $model = Mage::getModel('easyshippingrules/custommethod');
        $model->load($id);

        Mage::register('current_easyshippingrules_custom_method', $model);

        $this->loadLayout();

        $this
            ->getLayout()
            ->getBlock('rules_grid_serializer')
            ->setRules($this->getRequest()->getPost('custom_method_rules', null));

        $this->renderLayout();
    }

    /**
     * Get rules grid
     */
    public function rulesGridAction()
    {
        $id = $this->getRequest()->getParam('id');

        $model = Mage::getModel('easyshippingrules/custommethod');
        $model->load($id);

        Mage::register('current_easyshippingrules_custom_method', $model);

        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Custom Method mass delete action
     */
    public function massDeleteAction()
    {
        $custommethodIds = $this->getRequest()->getParam('custom_method');

        if (!is_array($custommethodIds)) {
            $this->_getSession()->addError($this->__('Please select custom method(s).'));
        } else {
            if (!empty($custommethodIds)) {
                try {
                    foreach ($custommethodIds as $custommethodId) {
                        Mage::getSingleton('easyshippingrules/custommethod')
                            ->load($custommethodId)
                            ->delete();
                    }

                    $this->_getSession()->addSuccess(
                        Mage::helper('adminhtml')->__('Total of %d record(s) have been deleted.', count($custommethodIds))
                    );
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            }
        }

        $this->_redirect('*/*/index');
    }

    /**
     * Custom Method mass update carrier action
     */
    public function massUpdateCarrierAction()
    {
        $custommethodIds = $this->getRequest()->getParam('custom_method');
        $carrier         = (int) $this->getRequest()->getParam('carrier');

        if (!is_array($custommethodIds)) {
            $this->_getSession()->addError($this->__('Please select custom method(s).'));
        } else {
            if (!empty($custommethodIds)) {
                try {
                    foreach ($custommethodIds as $custommethodId) {
                        Mage::getSingleton('easyshippingrules/custommethod')
                            ->load($custommethodId)
                            ->setEasyshippingrulesCarrierId($carrier)
                            ->save();
                    }

                    $this->_getSession()->addSuccess(
                        Mage::helper('adminhtml')->__('Total of %d record(s) have been deleted.', count($custommethodIds))
                    );
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            }
        }

        $this->_redirect('*/*/index');
    }

    /**
     * Custom Method mass update status action
     */
    public function massUpdateStatusAction()
    {
        $custommethodIds = $this->getRequest()->getParam('custom_method');
        $status          = (int) $this->getRequest()->getParam('status');

        if (!is_array($custommethodIds)) {
            $this->_getSession()->addError($this->__('Please select custom method(s).'));
        } else {
            if (!empty($custommethodIds)) {
                try {
                    foreach ($custommethodIds as $custommethodId) {
                        Mage::getSingleton('easyshippingrules/custommethod')
                            ->load($custommethodId)
                            ->setIsActive($status)
                            ->save();
                    }

                    $this->_getSession()->addSuccess(
                        Mage::helper('adminhtml')->__('Total of %d record(s) have been deleted.', count($custommethodIds))
                    );
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            }
        }

        $this->_redirect('*/*/index');
    }

    /**
     * Custom Method mass update price action action
     */
    public function massUpdatePriceActionAction()
    {
        $custommethodIds = $this->getRequest()->getParam('custom_method');
        $priceAction     = $this->getRequest()->getParam('price_action');

        if (!is_array($custommethodIds)) {
            $this->_getSession()->addError($this->__('Please select custom method(s).'));
        } else {
            if (!empty($custommethodIds)) {
                try {
                    foreach ($custommethodIds as $custommethodId) {
                        Mage::getSingleton('easyshippingrules/custommethod')
                            ->load($custommethodId)
                            ->setPriceAction($priceAction)
                            ->save();
                    }

                    $this->_getSession()->addSuccess(
                        Mage::helper('adminhtml')->__('Total of %d record(s) have been deleted.', count($custommethodIds))
                    );
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            }
        }

        $this->_redirect('*/*/index');
    }

    /**
     * Custom Method mass update price / percentage action
     */
    public function massUpdatePricePercentageAction()
    {
        $custommethodIds = $this->getRequest()->getParam('custom_method');
        $pricePercentage = (float) $this->getRequest()->getParam('price_percentage');

        if (!is_array($custommethodIds)) {
            $this->_getSession()->addError($this->__('Please select custom method(s).'));
        } else {
            if (!empty($custommethodIds)) {
                try {
                    foreach ($custommethodIds as $custommethodId) {
                        Mage::getSingleton('easyshippingrules/custommethod')
                            ->load($custommethodId)
                            ->setPricePercentage($pricePercentage)
                            ->save();
                    }

                    $this->_getSession()->addSuccess(
                        Mage::helper('adminhtml')->__('Total of %d record(s) have been deleted.', count($custommethodIds))
                    );
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            }
        }

        $this->_redirect('*/*/index');
    }
}
