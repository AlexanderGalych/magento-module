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
 * Symmetrics Management module observer class.
 *
 * @category  Symmetrics
 * @package   Symmetrics_Manager
 * @author    symmetrics - a CGI Group brand <info@symmetrics.de>
 * @author    Alex Galych <aleksandr.galych@symmetrics.de>
 * @copyright 2014 symmetrics - a CGI Group brand
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
class Symmetrics_Manager_Model_Observer extends Mage_Core_Model_Abstract
{
    /**
     * Manage workers scripts by cron.
     *
     * @throws Exception
     *
     * @return void
     */
    function manageWorkersScripts()
    {
        $workersNumber = (int) Mage::getStoreConfig('manager/general/workers_number');
        $workersCollection = Mage::getModel('manager/worker')->getCollection();
        $delta = $workersCollection->getSize() - $workersNumber;
        $callbackFunctions = Mage::getStoreConfig('callback_function');

        foreach ($workersCollection as $worker) {
            $isChanged = false;
            switch ($worker->getStatus()) {
                case Symmetrics_Manager_Model_Worker::STATUS_RUNNING:
                    if (!$worker->getPid()) {
                        $worker->setPid(Mage::getModel('manager/worker')->addWorker());
                        $isChanged = true;
                    }
                    break;
                case Symmetrics_Manager_Model_Worker::STATUS_STOPPED:
                    if ($worker->getPid()) {
                        $worker->killWorker($worker->getPid());
                        $isChanged = true;
                    }
                    break;

                default:
                    break;
            }
            if ($isChanged) {
                $worker->save();
            }
        }

        if ($delta < 0) {
            while ($delta < 0) {
                $worker = Mage::getModel('manager/worker');
                $worker->setData(
                    array(
                        'callback' => (count($callbackFunctions)) ? key($callbackFunctions) : 'not defined',
                        'status' => Symmetrics_Manager_Model_Worker::STATUS_CREATED,
                    )
                );
                $worker->save();
                $delta++;
            }
        } elseif ($delta > 0) {
            $collection = Mage::getModel('manager/worker')->getCollection()
                ->setOrder('status', 'ASC')
                ->setCurPage(0)
                ->setPageSize($delta);
            foreach ($collection as $worker) {
                $worker->killWorker($worker->getPid());
                $worker->delete();
            }
        }
    }
}