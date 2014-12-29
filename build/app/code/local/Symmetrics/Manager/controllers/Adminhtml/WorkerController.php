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
     * Initialize process object by request.
     *
     * @return Mage_Index_Model_Process|false
     */
    protected function _initWorker()
    {
        $workerId = $this->getRequest()->getParam('worker');
        if ($workerId) {
            /** @var $worker Symmetrics_Manager_Model_Worker */
            $worker = Mage::getModel('manager/worker')->load($workerId);
            if ($worker->getId()) {
                return $worker;
            }
        }
        return false;
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
     * Process detail and edit action.
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
                 ->_title($this->__($worker->getPid()));

            Mage::register('current_worker', $worker);
            $this->loadLayout();
            $this->renderLayout();
        } else {
            $this->_getSession()->addError(
                Mage::helper('index')->__('Cannot initialize the worker process.')
            );
            $this->_redirect('*/*/list');
        }
    }

    /**
     * Save process data.
     *
     * @return void
     */
    public function saveAction()
    {
        /** @var $worker Symmetrics_Manager_Model_Worker */
        $worker = $this->_initWorker();
        $postData = $this->getRequest()->getPost();
        if ($worker && $postData) {
            try {
                $worker->addData($postData);
                $worker->save();
                $this->_getSession()->addSuccess(
                    Mage::helper('manager')->__('The worker has been saved.')
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
}
