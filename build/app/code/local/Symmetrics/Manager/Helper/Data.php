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
 * Workers Management helper
 *
 * @category  Symmetrics
 * @package   Symmetrics_Manager
 * @author    symmetrics - a CGI Group brand <info@symmetrics.de>
 * @author    Alex Galych <aleksandr.galych@symmetrics.de>
 * @copyright 2014 symmetrics - a CGI Group brand
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
class Symmetrics_Manager_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * Get array of available worker's statuses.
     *
     * @return array.
     */
    public function getWorkerStatuses()
    {
        return array(
            Symmetrics_Manager_Model_Worker::STATUS_CREATED => $this->__('Created'),
            Symmetrics_Manager_Model_Worker::STATUS_STOPPED => $this->__('Stopped'),
            Symmetrics_Manager_Model_Worker::STATUS_PENDING => $this->__('Pending'),
            Symmetrics_Manager_Model_Worker::STATUS_RUNNING => $this->__('Running'),
            Symmetrics_Manager_Model_Worker::STATUS_WAITING => $this->__('Waiting'),
        );
    }

    /**
     * Get array of available worker's callback functions.
     *
     * @return array.
     */
    public function getWorkerCallbackFunctions()
    {
        $callbackFunctions = Mage::getStoreConfig('callback_function');
        return $callbackFunctions;
    }

    /**
     * Manage workers quantity by config value.
     *
     * @param int $delta Number of workers diff.
     *
     * @return void
     *
     * @throws Exception
     */
    public function manageWorkersQuantity($delta)
    {
        $callbackFunctions = Mage::getStoreConfig('callback_function');
        if ($delta < 0) {
            while ($delta < 0) {
                $worker = Mage::getModel('manager/worker');
                $worker->setData(
                    array(
                        'callback' => (count($callbackFunctions)) ? key($callbackFunctions) : 'not defined',
                        'status' => Symmetrics_Manager_Model_Worker::STATUS_CREATED,
                    )
                )->save();
                $delta++;
            }
        } elseif ($delta > 0) {
            $collection = Mage::getModel('manager/worker')->getCollection()
                ->setOrder('status', 'ASC')
                ->setCurPage(0)
                ->setPageSize($delta);
            foreach ($collection as $worker) {
                $worker->killWorker();
                $worker->delete();
            }
        }
    }
}
