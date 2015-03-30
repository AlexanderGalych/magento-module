<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category  Symmetrics
 * @package   Symmetrics_Manager
 * @author    symmetrics - a CGI Group brand <info@symmetrics.de>
 * @author    Alex Galych <aleksandr.galych@symmetrics.de>
 * @copyright 2014 symmetrics - a CGI Group brand
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */

/**
 * Workers Management controller
 *
 * @category  Symmetrics
 * @package   Symmetrics_Manager
 * @author    symmetrics - a CGI Group brand <info@symmetrics.de>
 * @author    Alex Galych <aleksandr.galych@symmetrics.de>
 * @copyright 2014 symmetrics - a CGI Group brand
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
class Symmetrics_Manager_Adminhtml_WorkerController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Initialize worker object by request.
     *
     * @return Mage_Index_Model_Process|false
     */
    protected function _initWorker()
    {
        /** @var $worker Symmetrics_Manager_Model_Worker */
        $worker = Mage::getModel('manager/worker');
        $workerId = $this->getRequest()->getParam('worker');
        if ($workerId) {
            $worker->load($workerId);
            if (!$worker->getId()) {
                $worker = false;
            }
        }
        return $worker;
    }

    /**
     * Display processes grid action.
     *
     * @return void
     */
    public function listAction()
    {
        $this->_title($this->__('System'))->_title($this->__('Worker Management'));

        $this->loadLayout();
        $this->_setActiveMenu('system/worker');
        $this->renderLayout();
    }

    /**
     * Worker detail and edit action.
     *
     * @return void
     */
    public function editAction()
    {
        /** @var $worker Symmetrics_Manager_Model_Worker */
        $worker = $this->_initWorker();
        if ($worker) {
            $this->_title($worker->getPid());

            $this->_title($this->__('System'))
                 ->_title($this->__('Workers Management'))
                 ->_title($this->__(($worker->getId()) ? $worker->getId() : 'New Worker'));

            Mage::register('current_worker', $worker);
            $this->loadLayout();
            $this->renderLayout();
        } else {
            $this->_getSession()->addError(
                Mage::helper('manager')->__('Cannot initialize the worker process.')
            );
            $this->_redirect('*/*/list');
        }
    }

    /**
     * Save worker data.
     *
     * @return void
     */
    public function saveAction()
    {
        $workersNumber = $this->getRequest()->getParam('number_of_workers', 1);
        try {
            while ($workersNumber--) {
                /** @var $worker Symmetrics_Manager_Model_Worker */
                $worker = $this->_initWorker();
                $postData = $this->getRequest()->getPost();
                if ($worker && $postData) {
                    if (isset($postData['end_time']) && $postData['end_time']) {
                        $format = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
                        $postData['end_time'] = (string)Mage::app()->getLocale()
                            ->utcDate(Mage::app()->getStore(), $postData['end_time'], true, $format);
                    }
                    $worker->addData($postData);
                    if ($workersNumber) {
                        $worker->setDescription($worker->getDescription() . ' - ' . $workersNumber);
                    }
                    $worker->save();
                    $this->_getSession()->addSuccess(
                        Mage::helper('manager')->__(sprintf('The worker #%d has been saved.', $worker->getId()))
                    );

                } else {
                    $this->_getSession()->addError(
                        Mage::helper('manager')->__('Cannot initialize the worker process.')
                    );
                }
            }
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e, Mage::helper('manager')->__('There was a problem with saving worker.')
            );
        }
        $this->_redirect('*/*/list');
    }

    /**
     * Add worker entry.
     *
     * @return void
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * Remove worker entry.
     *
     * @return void
     */
    public function removeAction()
    {
        /** @var $worker Symmetrics_Manager_Model_Worker */
        $worker = $this->_initWorker();
        if ($worker) {
            try {
                $worker->delete();
                $this->_getSession()->addSuccess(
                    Mage::helper('manager')->__('The worker "%d" has been deleted.', $worker->getId())
                );
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addException(
                    $e, Mage::helper('manager')->__('There was a problem with saving worker.')
                );
            }
            $this->_redirect('*/*/list');
        } else {
            $this->_getSession()->addError(
                Mage::helper('manager')->__('Cannot initialize the worker process.')
            );
            $this->_redirect('*/*/list');
        }
    }

    /**
     * Run selected workers.
     *
     * @return void
     */
    public function massStartAction()
    {
        $this->_setMassActionWorkersStatus(Symmetrics_Manager_Model_Worker::STATUS_RUNNING);
    }

    /**
     * Set waiting status selected workers.
     *
     * @return void
     */
    public function massWaitingAction()
    {
        $this->_setMassActionWorkersStatus(Symmetrics_Manager_Model_Worker::STATUS_WAITING);
    }

    /**
     * Stop selected workers.
     *
     * @return void
     */
    public function massStopAction()
    {
        $this->_setMassActionWorkersStatus(Symmetrics_Manager_Model_Worker::STATUS_STOPPED);
    }

    /**
     * Remove selected workers.
     *
     * @return void
     */
    public function massRemoveAction()
    {
        /* @var $worker Symmetrics_Manager_Model_Worker */
        $worker    = Mage::getSingleton('manager/worker');
        $workerIds = $this->getRequest()->getParam('worker');
        if (empty($workerIds) || !is_array($workerIds)) {
            $this->_getSession()->addError(Mage::helper('manager')->__('Please select Workers'));
        } else {
            try {
                $counter = $worker->removeWorkersByIds($workerIds);
                $this->_getSession()->addSuccess(
                    Mage::helper('manager')->__('Total of %d worker(es) were removed.', $counter)
                );
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addException(
                    $e, Mage::helper('manager')->__('Cannot initialize the worker process.')
                );
            }
        }
        $this->_redirect('*/*/list');
    }

    /**
     * Set workers status by mass-action.
     *
     * @param int $status Worker status.
     *
     * @return void
     */
    protected function _setMassActionWorkersStatus($status)
    {
        /* @var $worker Symmetrics_Manager_Model_Worker */
        $worker = Mage::getSingleton('manager/worker');
        $workerIds = $this->getRequest()->getParam('worker');
        if (empty($workerIds) || !is_array($workerIds)) {
            $this->_getSession()->addError(Mage::helper('manager')->__('Please select Workers'));
        } else {
            try {
                $counter = $worker->setWorkerStatusByIds($status, $workerIds);
                $this->_getSession()->addSuccess(
                    Mage::helper('manager')->__('Total of %d worker(es) have update status.', $counter)
                );
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addException(
                    $e, Mage::helper('manager')->__('Cannot initialize the worker process.')
                );
            }
        }
        $this->_redirect('*/*/list');
    }
}
