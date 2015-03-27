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
     * Path to log folder.
     */
    const PATH_TO_LOG = '/workers/';

    /**
     * Log folder permissions.
     */
    const LOG_FOLDER_PERMISSIONS = 0777;

    /**
     * Log file.
     */
    const LOG_FILE = 'processor_%PID%_log.log';

    /**
     *
     */
    const GLOBAL_LOG = 4;
    /**
     *
     */
    const GLOBAL_DATA_LOG = 5;
    /**
     *
     */
    const GLOBAL_ERROR_LOG = 2;
    /**
     *
     */
    const GLOBAL_WARNING_LOG = 3;
    /**
     *
     */
    const GLOBAL_FATAL_LOG = 1;

    /**
     *
     */
    const GLOBAL_LOG_PREFIX = 'GLOBAL';

    /**
     *
     */
    const ITERATION_LOG = 4;
    /**
     *
     */
    const ITERATION_DATA_LOG = 5;
    /**
     *
     */
    const ITERATION_ERROR_LOG = 2;
    /**
     *
     */
    const ITERATION_WARNING_LOG = 3;

    /**
     *
     */
    const ITERATION_LOG_PREFIX = 'ITERATION';

    /**
     *
     */
    const BOX_LOG = 4;

    /**
     *
     */
    const BOX_DATA_LOG = 5;

    /**
     *
     */
    const BOX_ERROR_LOG = 2;

    /**
     *
     */
    const BOX_WARNING_LOG = 3;

    /**
     *
     */
    const BOX_LOG_PREFIX = 'BOX';

    /**
     *
     */
    const BOX_OPERATION_LOG = 4;

    /**
     *
     */
    const BOX_OPERATION_DATA_LOG = 6;

    /**
     *
     */
    const BOX_OPERATION_ERROR_LOG = 2;

    /**
     *
     */
    const BOX_OPERATION_WARNING_LOG = 3;

    /**
     *
     */
    const BOX_OPERATION_LOG_PREFIX = 'BOX OPERATION';

    /**
     *
     */
    const POST_ITERATION_LOG = 4;

    /**
     *
     */
    const POST_ITERATION_DATA_LOG = 5;

    /**
     *
     */
    const POST_ITERATION_ERROR_LOG = 2;

    /**
     *
     */
    const POST_ITERATION_WARNING_LOG = 3;

    /**
     *
     */
    const POST_ITERATION_LOG_PREFIX = 'POST_ITERATION';

    /**
     * Worker log file connection.
     *
     * @var null|resource
     */
    protected $_logConnection = null;

    /**
     * Worker PID.
     *
     * @var int|null
     */
    protected $_workerPid = null;

    /**
     * Worker log level.
     *
     * @var int|null
     */
    protected $_logLevel = null;

    /**
     * Default date format.
     *
     * @var string
     */
    protected $_dateFormat = 'Y-m-d H:i:s';

    /**
     * Log block separator.
     *
     * @var string
     */
    protected $_logBlockSeparator = ' | ';

    /**
     * * Log tags separator.
     *
     * @var string
     */
    protected $_logTagsSeparator = ' - ';

    /**
     * Init object method.
     *
     * @param array $params Array of passed parameters.
     *
     * @throws Exception
     */
    public function __construct(array $params)
    {
        $this->_workerPid = $params['pid'];
        $this->_logLevel = $params['log_level'];
        $logFolder = Mage::getBaseDir('var') . self::PATH_TO_LOG;
        if (!file_exists($logFolder)) {
            mkdir($logFolder, self::LOG_FOLDER_PERMISSIONS, true);
        }
        $filePath = $logFolder . str_replace('%PID%', $this->_workerPid, self::LOG_FILE);
        $this->_logConnection = fopen($filePath, "w");
        if ($this->_logConnection) {
            $msg = 'Worker PID: ' . $this->_workerPid . " start \n";
            $this->_writeLog($msg, array('CONSTRUCT'), self::GLOBAL_LOG_PREFIX);
        }
    }

    /**
     * Destruct object properties.
     *
     * @return void
     */
    public function __destruct()
    {
        $msg = "Worker PID: " . $this->_workerPid . " has been stopped";
        $this->_writeLog($msg, array('DESTRUCT'), self::GLOBAL_LOG_PREFIX);
        fclose($this->_logConnection);
    }

    /**
     * Set date format.
     *
     * @param string $format
     *
     * @return void
     */
    public function setDateFormat($format)
    {
        if (!is_null($format) && is_string($format)) {
            $this->_dateFormat = $format;
        }
    }

    /**
     * Get current date.
     *
     * @return string
     */
    protected function _getDate()
    {
        return Mage::getModel('core/date')->date($this->_dateFormat);
    }

    /**
     * Prepare log string.
     *
     * @param string $log
     * @param array  $tags
     * @param string $section
     * @param bool   $nextLine
     *
     * @return string
     */
    protected function _prepareLogString($log, $tags, $section, $nextLine)
    {
        $logPrefix = $this->_getDate() . $this->_logBlockSeparator . $section . $this->_logBlockSeparator;
        $logPrefix .= (!empty($tags) ? join($this->_logTagsSeparator, $tags) : 'NO TAGS') . $this->_logBlockSeparator;
        $logSuffix = ($nextLine) ? PHP_EOL : "";
        return $logPrefix . $log . $logSuffix;
    }

    /**
     * Write log to log file.
     *
     * @param string $log
     * @param array  $tags
     * @param string $section
     * @param bool   $nextLine
     *
     * @return void
     */
    protected function _writeLog($log = '', $tags, $section = 'UNKNOWN', $nextLine = true)
    {
        if ($this->_logConnection) {
            fwrite($this->_logConnection, $this->_prepareLogString($log, $tags, $section, $nextLine));
        }
    }

    /**
     * Check method log level on allowed.
     *
     * @param int $methodLogLevel Log report method level.
     *
     * @return bool
     */
    protected function _isAllowed($methodLogLevel)
    {
        return $methodLogLevel < $this->_logLevel;
    }
}