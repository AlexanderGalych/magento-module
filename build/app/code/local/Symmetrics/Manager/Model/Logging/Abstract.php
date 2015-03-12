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
 * @copyright 2015 symmetrics - a CGI Group brand
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */

/**
 * Abstract class for Workers Logging functions.
 *
 * @category  Symmetrics
 * @package   Symmetrics_Manager
 * @author    symmetrics - a CGI Group brand <info@symmetrics.de>
 * @author    Alex Galych <aleksandr.galych@symmetrics.de>
 * @copyright 2015 symmetrics - a CGI Group brand
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
abstract class Symmetrics_Manager_Model_Logging_Abstract
{
    /**
     * Path to log file.
     */
    const PATH_TO_LOG = '/workers/processor_%PID%_log.log';

    /**
     * @var null|resource
     */
    protected $_logConnection = null;

    protected $_workerPid = null;

    protected $_logLevel = null;

    /**
     * Init object method.
     *
     * @param int $pid      Worker PID.
     * @param int $logLevel Logger level id.
     *
     * @throws Exception
     */
    public function __construct($pid, $logLevel)
    {
        $this->_workerPid = $pid;
        $this->_logLevel = $logLevel;
        $filePath = Mage::getBaseDir('var') . str_replace('%PID%', $pid, self::PATH_TO_LOG);
        $this->_logConnection = fopen($filePath, "w");
        if ($this->_logConnection) {
            $this->_writeLog('php processor pid: ' . $pid . "start \n");
        }
    }

    /**
     * Destruct object properties.
     *
     * @return void
     */
    public function __destruct()
    {
        $this->_writeLog('php processor pid: ' . $this->_workerPid . "end \n");
        fclose($this->_logConnection);
    }

    /**
     * Write log to log file.
     *
     * @param string $log
     * @param bool   $nextLine
     *
     * @return void
     */
    protected function _writeLog($log = '', $nextLine = true)
    {
        fwrite($this->_logConnection,  $log . (($nextLine) ? "\n" : ""));
    }

    /**
     * @param $logType
     *
     * @return bool
     */
    protected function _isAllowed($logType)
    {
        return true;
    }
}