<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * Adminhtml Carrier controller
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Adminhtml_Easyshippingrules_CarrierController
    extends Mage_Adminhtml_Controller_Action
{
    /**
     * Init action
     *
     * @return MatheusGontijo_EasyShippingRules_Adminhtml_Easyshippingrules_CarrierController
     */
    protected function _initAction()
    {
        $this
            ->loadLayout()
            ->_setActiveMenu('easyshippingrules/carrier')
            ->_addBreadcrumb(
                $this->__('Easy Shipping Rules'),
                $this->__('Easy Shipping Rules')
            );

        return $this;
    }

    /**
     * Carrier index action
     */
    public function indexAction()
    {
        $this->_title($this->__('Easy Shipping Rules'))->_title($this->__('Carrier'));

        $this
            ->_initAction()
            ->_addBreadcrumb(
                $this->__('Easy Shipping Rules'),
                $this->__('Easy Shipping Rules')
            )
            ->renderLayout();
    }

    /**
     * Carrier grid action
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Carrier new action
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * Carrier edit action
     */
    public function editAction()
    {
        $id    = $this->getRequest()->getParam('id');
        $model = Mage::getModel('easyshippingrules/carrier');

        if ($id) {
            $model->load($id);

            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(
                    $this->__('This carrier no longer exists.')
                );

                $this->_redirect('*/*');

                return;
            }
        }

        $this->_title($model->getId() ? $model->getName() : $this->__('New Carrier'));

        $data = Mage::getSingleton('adminhtml/session')->getPageData(true);

        if (!empty($data)) {
            $model->addData($data);
        }

        Mage::register('current_easyshippingrules_carrier', $model);

        $this
            ->_initAction()
            ->getLayout()
            ->getBlock('admin.easyshippingrules.carrier.edit')
            ->setData('action', $this->getUrl('*/*/save'));

        $this
            ->_addBreadcrumb(
                $id ? $this->__('Edit Carrier')
                    : $this->__('New Carrier'),
                $id ? $this->__('Edit Carrier')
                    : $this->__('New Carrier')
            )
            ->renderLayout();
    }

    /**
     * Carrier save action
     */
    public function saveAction()
    {
        if ($this->getRequest()->getPost()) {
            try {
                /** @var $model MatheusGontijo_EasyShippingRules_Model_Carrier */
                $model = Mage::getModel('easyshippingrules/carrier');
                $data  = $this->getRequest()->getPost();
                $id    = $this->getRequest()->getParam('easyshippingrules_carrier_id');

                if ($id) {
                    $model->load($id);

                    if ($id != $model->getId()) {
                        Mage::throwException($this->__('Wrong carrier specified.'));
                    }
                }

                $model->setData($data);

                $session = Mage::getSingleton('adminhtml/session');

                $session->setPageData($model->getData());

                $model->save();

                $session->addSuccess($this->__('The carrier has been saved.'));
                $session->setPageData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));

                    return;
                }

                $this->_redirect('*/*/');

                return;
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());

                $id = (int) $this->getRequest()->getParam('easyshippingrules_carrier_id');

                if (!empty($id)) {
                    $this->_redirect('*/*/edit', array('id' => $id));
                } else {
                    $this->_redirect('*/*/new');
                }

                return;
            } catch (Exception $e) {
                $this->_getSession()->addError(
                    $this->__('An error occurred while saving the carrier data. Please review the log and try again.')
                );

                Mage::logException($e);

                Mage::getSingleton('adminhtml/session')->setPageData($data);

                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('easyshippingrules_carrier_id')));

                return;
            }
        }

        $this->_redirect('*/*/');
    }

    /**
     * Carrier delete action
     */
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $model = Mage::getModel('easyshippingrules/carrier');

                $model->load($id);
                $model->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    $this->__('The carrier has been deleted.')
                );

                $this->_redirect('*/*/');

                return;
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addError(
                    $this->__('An error occurred while deleting the carrier. Please review the log and try again.')
                );

                Mage::logException($e);

                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));

                return;
            }
        }

        Mage::getSingleton('adminhtml/session')->addError(
            $this->__('Unable to find a carrier to delete.')
        );

        $this->_redirect('*/*/');
    }

    /**
     * Carrier mass delete action
     */
    public function massDeleteAction()
    {
        $carrierIds = $this->getRequest()->getParam('carrier');

        if (!is_array($carrierIds)) {
            $this->_getSession()->addError($this->__('Please select carrier(s).'));
        } else {
            if (!empty($carrierIds)) {
                try {
                    foreach ($carrierIds as $carrierId) {
                        Mage::getSingleton('easyshippingrules/carrier')
                            ->load($carrierId)
                            ->delete();
                    }

                    $this->_getSession()->addSuccess(
                        Mage::helper('adminhtml')->__('Total of %d record(s) have been deleted.', count($carrierIds))
                    );
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            }
        }

        $this->_redirect('*/*/index');
    }

    /**
     * Carrier mass status update action
     */
    public function massStatusAction()
    {
        $carrierIds = (array) $this->getRequest()->getParam('carrier');
        $status     = $this->getRequest()->getParam('status');

        if (!is_array($carrierIds)) {
            $this->_getSession()->addError($this->__('Please select carrier(s).'));
        } else {
            try {
                foreach ($carrierIds as $carrierId) {
                    $carrier = Mage::getModel('easyshippingrules/carrier')->load($carrierId);

                    if ($carrier->getId()) {
                        $carrier
                            ->setIsActive($status)
                            ->save();
                    }
                }

                $this->_getSession()->addSuccess(
                    Mage::helper('adminhtml')->__('Total of %d record(s) have been updated.', count($carrierIds))
                );
            } catch (Mage_Core_Model_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()
                    ->addException($e, $this->__('An error occurred while updating the carrier(s) status.'));
            }
        }

        $this->_redirect('*/*/');
    }
}
