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
     * Process status pending.
     */
    const STATUS_PENDING = 2;
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
    const LOG_FILE_PATH = '/var/www/rabbitmq/php/logs/processor_%PID%_log.txt';

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
     * Add worker to system and set PID.
     *
     * @return void
     */
    public function addWorker()
    {
        $this->setPid(exec('php /var/www/rabbitmq/php/processor.php > /dev/null 2>/dev/null &  echo $!'));
    }

    /**
     * Delete worker script from system by PID.
     *
     * @return void
     */
    public function killWorker()
    {
        if ($this->pid && posix_kill($this->pid, 9)) {
            unlink(str_replace('%PID%', $this->pid, self::LOG_FILE_PATH));
        }
        $this->pid = null;
        $this->status = Symmetrics_Manager_Model_Worker::STATUS_STOPPED;
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
        $isChanged = false;
        $shouldDieNow = strtotime($this->getEndTime()) < strtotime(Mage::getModel('core/date')->date('Y-m-d H:i:s'));

        if ($this->getStatus() != Symmetrics_Manager_Model_Worker::STATUS_STOPPED
            && $this->getEndTime() && $shouldDieNow
        ) {
            $this->killWorker();
            $isChanged = true;
        } else {
            switch ($this->getStatus()) {
                case Symmetrics_Manager_Model_Worker::STATUS_RUNNING:
                    if ($this->getEndTime()) {
                        $this->addWorker();
                        $isChanged = true;
                    }
                    break;
                case Symmetrics_Manager_Model_Worker::STATUS_STOPPED:
                    if ($this->getPid()) {
                        $this->killWorker();
                        $isChanged = true;
                    }
                    break;

                default:
                    break;
            }
        }
        if ($isChanged) {
            $this->save();
        }
    }
}