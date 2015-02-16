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
 * Worker main class.
 *
 * @category  Symmetrics
 * @package   Symmetrics_Manager
 * @author    symmetrics - a CGI Group brand <info@symmetrics.de>
 * @author    Alex Galych <aleksandr.galych@symmetrics.de>
 * @copyright 2014 symmetrics - a CGI Group brand
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
class Symmetrics_Manager_Model_Worker extends Mage_Core_Model_Abstract
{
    /**
     * Process status running.
     */
    const STATUS_RUNNING = 4;
    /**
     * Process status waiting.
     */
    const STATUS_WAITING = 3;
    /**
     * Process status finished.
     */
    const STATUS_FINISHED = 2;
    /**
     * Process status stopped.
     */
    const STATUS_STOPPED = 1;
    /**
     * Process status created.
     */
    const STATUS_CREATED = 0;
    /**
     * Path to worker log file.
     */
    const LOG_FILE_PATH = '/workers/processor_%PID%_log.log';
    /**
     * Path to callback functions.
     */
    const WORKER_PATH =
        'php %PATH%/shell/worker_manager/worker.php --callback = %CALLBACK% > /dev/null 2>/dev/null &  echo $!';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'manager_worker';
    /**
     * Collection of available workers.
     *
     * @var null
     */
    protected $_workersCollection = null;

    /**
     * Constructor for Model Social.
     *
     * @return null
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('manager/worker');
    }

    /**
     * Get collection of all available workers.
     *
     * @return Symmetrics_Manager_Model_Resource_Worker_Collection
     */
    protected function _getWorkersCollection()
    {
        if (is_null($this->_workersCollection)) {
            $this->_workersCollection = Mage::getModel('manager/worker')->getCollection();
        }
        return $this->_workersCollection;
    }

    /**
     * Get date in GMT format.
     *
     * @param int|string $input Date in current timezone.
     *
     * @return string
     */
    protected function _getGmtDate($input = null)
    {
        return Mage::getSingleton('core/date')->gmtDate('Y-m-d H:i:s', $input);
    }

    /**
     * Get worker process by specific id
     *
     * @param int $workerId Worker proccess id.
     *
     * @return Symmetrics_Manager_Model_Worker|false
     */
    public function getWorkerById($workerId)
    {
        foreach ($this->_getWorkersCollection() as $worker) {
            if ($worker->getId() == $workerId) {
                return $worker;
            }
        }
        return false;
    }

    /**
     * Add worker to system and set PID.
     *
     * @return void
     */
    public function addWorker()
    {
        $path = str_replace(
            array('%PATH%', '%CALLBACK%'),
            array(Mage::getBaseDir(), $this->getCallback()),
            self::WORKER_PATH
        );
        $this->setPid(exec($path));
    }

    /**
     * Delete worker script from system by PID.
     *
     * @return void
     */
    public function killWorker()
    {
        if ($this->getPid()) {
            posix_kill($this->getPid(), 14);
            $logPath = Mage::getBaseDir('var') . self::LOG_FILE_PATH;
            unlink(str_replace('%PID%', $this->getPid(), $logPath));
            $this->setPid(null);
        }
    }

    /**
     * Check worker is running by pid.
     *
     * @return bool
     */
    public function checkWorkerIsRunning()
    {
        return $this->getPid() && posix_kill($this->getPid(), 0);
    }

    /**
     * Set worker status and additional data.
     *
     * @param int $status Required status.
     *
     * @return void
     */
    public function setWorkerStatus($status)
    {
        $isChanged = false;
        $this->setStatus($status);
        $date = $this->_getGmtDate();
        switch ($status) {
            case self::STATUS_RUNNING:
                if (!$this->getPid()) {
                    $this->addWorker();
                    $this->setFinishedTime(null);
                    $this->setCreationTime($date);
                    $isChanged = true;
                }
                break;
            case self::STATUS_FINISHED:
                if ($this->getPid()) {
                    $this->killWorker();
                    $this->setEndTime(null);
                    $this->setFinishedTime($date);
                    $isChanged = true;
                }
                break;
            case self::STATUS_STOPPED:
                if ($this->getPid()) {
                    $this->killWorker();
                    $this->setEndTime(null);
                    $this->setFinishedTime($date);
                    $isChanged = true;
                }
                break;
            default:
                break;
        }
        if ($isChanged) {
            $this->save();
        }
    }

    /**
     * Set new callback function to the existed worker.
     *
     * @param string $callback Callback function.
     *
     * @return string
     *
     * ToDo: should be implemented.
     */
    public function setWorkerCallback($callback)
    {
        return $callback;
    }

    /**
     * Update workers statuses by ids array.
     *
     * @param int   $status Selected worker status.
     * @param array $ids    Workers ids.
     *
     * @return int
     */
    public function setWorkerStatusByIds($status, $ids)
    {
        /* @var $collection Symmetrics_Manager_Model_Resource_Worker_Collection */
        $collection = Mage::getModel('manager/worker')->getCollection()
            ->addFieldToFilter('entry_id', array('in' => $ids))
            ->addFieldToFilter('status', array('neq' => $status));
        foreach ($collection as $worker) {
            $worker->setStatus($status);
        }
        return $collection->save()->count();
    }

    /**
     * Remove workers by ids.
     *
     * @param array $ids Workers ids.
     *
     * @return int
     */
    public function removeWorkersByIds($ids)
    {
        /* @var $collection Symmetrics_Manager_Model_Resource_Worker_Collection */
        $collection = Mage::getModel('manager/worker')->getCollection()
            ->addFieldToFilter('entry_id', array('in' => $ids));
        foreach ($collection as $worker) {
            $worker->delete();
        }
        return $collection->save()->count();
    }

    /**
     * Manage worker state by cron.
     *
     * @return void
     *
     * @throws Exception
     */
    public function manageWorkerState()
    {
        if ($this->getStatus() == self::STATUS_RUNNING) {
            $shouldDieNow = strtotime($this->getEndTime()) < strtotime($this->_getGmtDate());
            if ($this->getEndTime() && $shouldDieNow) {
                $this->setWorkerStatus(self::STATUS_STOPPED);
            } elseif (!$this->checkWorkerIsRunning()) {
                $this->setWorkerStatus(self::STATUS_FINISHED);
            }
        }
    }
}