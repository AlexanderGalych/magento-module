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
 * Worker Logger model.
 *
 * @category  Symmetrics
 * @package   Symmetrics_Manager
 * @author    symmetrics - a CGI Group brand <info@symmetrics.de>
 * @author    Alex Galych <aleksandr.galych@symmetrics.de>
 * @copyright 2015 symmetrics - a CGI Group brand
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
class Symmetrics_Manager_Model_Logging_Logger
    extends Symmetrics_Manager_Model_Logging_Abstract
    implements Symmetrics_Manager_Model_Logging_Interface
{
    /**
     *
     *
     * @param string $log  Log message.
     * @param array  $tags Array of message tags.
     *
     * @return void
     */
    public function logFatalError($log, $tags = array())
    {
        if ($this->_isAllowed(self::GLOBAL_FATAL_LOG)) {
            if (is_string($log)) {
                $msg = $log;
            } else if ($log instanceof Exception) {
                $msg = 'MESSAGE: ' . $log->getMessage() . '  ' . 'FILE: ' . $log->getFile()
                    . '  LINE: ' . $log->getLine();
            } else {
                $msg = (string) $log;
            }
            $this->_writeLog($msg, $tags, self::GLOBAL_LOG_PREFIX);
        }
    }

    /**
     *
     *
     * @param string $log  Log message.
     * @param array  $tags Array of message tags.
     *
     * @return void
     */
    public function logInfoMessage($log, $tags = array())
    {
        if ($this->_isAllowed(self::GLOBAL_LOG)) {
            $this->_writeLog($log, $tags, self::GLOBAL_LOG_PREFIX);
        }
    }

    /**
     *
     *
     * @param string $log  Log message.
     * @param array  $tags Array of message tags.
     *
     * @return void
     */
    public function logGlobalLevel($log, $tags = array())
    {
        if ($this->_isAllowed(self::GLOBAL_LOG)) {
            $this->_writeLog($log, $tags, self::GLOBAL_LOG_PREFIX);
        }
    }

    /**
     *
     *
     * @param string $log  Log message.
     * @param array  $tags Array of message tags.
     *
     * @return void
     */
    public function logGlobalStatus($log, $tags = array())
    {
        if ($this->_isAllowed(self::GLOBAL_LOG)) {
            $this->_writeLog($log, $tags, self::GLOBAL_LOG_PREFIX);
        }
    }

    /**
     *
     *
     * @param string $log  Log message.
     * @param array  $tags Array of message tags.
     *
     * @return void
     */
    public function logGlobalError($log, $tags = array())
    {
        if ($this->_isAllowed(self::GLOBAL_ERROR_LOG)) {
            $this->_writeLog($log, $tags, self::GLOBAL_LOG_PREFIX);
        }
    }

    /**
     *
     *
     * @param string $log Log message.
     *
     * @return void
     */
    public function logGlobalWarning($log, $tags = array())
    {
        if ($this->_isAllowed(self::GLOBAL_WARNING_LOG)) {
            $this->_writeLog($log, $tags, self::GLOBAL_LOG_PREFIX);
        }
    }

    /**
     *
     *
     * @param string $log Log message.
     *
     * @return void
     */
    public function logGlobalInputData($log, $tags = array())
    {
        if ($this->_isAllowed(self::GLOBAL_DATA_LOG)) {
            $this->_writeLog($log, $tags, self::GLOBAL_LOG_PREFIX);
        }
    }

    /**
     *
     *
     * @param string $log Log message.
     *
     * @return void
     */
    public function logGlobalOutputData($log, $tags = array())
    {
        if ($this->_isAllowed(self::GLOBAL_DATA_LOG)) {
            $this->_writeLog($log, $tags, self::GLOBAL_LOG_PREFIX);
        }
    }

    /**
     *
     *
     * @param string $log Log message.
     *
     * @return void
     */
    public function logIterationLevel($log, $tags = array())
    {
        if ($this->_isAllowed(self::ITERATION_LOG)) {
            $this->_writeLog($log, $tags, self::ITERATION_LOG_PREFIX);
        }
    }

    /**
     *
     *
     * @param string $log Log message.
     *
     * @return void
     */
    public function logIterationStatus($log, $tags = array())
    {
        if ($this->_isAllowed(self::ITERATION_LOG)) {
            $this->_writeLog($log, $tags, self::ITERATION_LOG_PREFIX);
        }
    }

    /**
     *
     *
     * @param string $log Log message.
     *
     * @return void
     */
    public function logIterationError($log, $tags = array())
    {
        if ($this->_isAllowed(self::ITERATION_ERROR_LOG)) {
            $this->_writeLog($log, $tags, self::ITERATION_LOG_PREFIX);
        }
    }

    /**
     *
     *
     * @param string $log Log message.
     *
     * @return void
     */
    public function logIterationWarning($log, $tags = array())
    {
        if ($this->_isAllowed(self::ITERATION_WARNING_LOG)) {
            $this->_writeLog($log, $tags, self::ITERATION_LOG_PREFIX);
        }
    }

    /**
     *
     *
     * @param string $log Log message.
     *
     * @return void
     */
    public function logIterationInputData($log, $tags = array())
    {
        if ($this->_isAllowed(self::ITERATION_DATA_LOG)) {
            $this->_writeLog($log, $tags, self::ITERATION_LOG_PREFIX);
        }
    }

    /**
     *
     *
     * @param string $log Log message.
     *
     * @return void
     */
    public function logIterationOutputData($log, $tags = array())
    {
        if ($this->_isAllowed(self::ITERATION_DATA_LOG)) {
            $this->_writeLog($log, $tags, self::ITERATION_LOG_PREFIX);
        }
    }

    /**
     *
     *
     * @param string $log Log message.
     *
     * @return void
     */
    public function logBoxLevel($log, $tags = array())
    {
        if ($this->_isAllowed(self::BOX_LOG)) {
            $this->_writeLog($log, $tags, self::BOX_LOG_PREFIX);
        }
    }

    /**
     *
     *
     * @param string $log Log message.
     *
     * @return void
     */
    public function logBoxStatus($log, $tags = array())
    {
        if ($this->_isAllowed(self::BOX_LOG)) {
            $this->_writeLog($log, $tags, self::BOX_LOG_PREFIX);
        }
    }

    /**
     *
     *
     * @param string $log Log message.
     *
     * @return void
     */
    public function logBoxError($log, $tags = array())
    {
        if ($this->_isAllowed(self::BOX_ERROR_LOG)) {
            $this->_writeLog($log, $tags, self::BOX_LOG_PREFIX);
        }
    }

    /**
     *
     *
     * @param string $log Log message.
     *
     * @return void
     */
    public function logBoxWarning($log, $tags = array())
    {
        if ($this->_isAllowed(self::BOX_WARNING_LOG)) {
            $this->_writeLog($log, $tags, self::BOX_LOG_PREFIX);
        }
    }

    /**
     *
     *
     * @param string $log Log message.
     *
     * @return void
     */
    public function logBoxInputData($log, $tags = array())
    {
        if ($this->_isAllowed(self::BOX_DATA_LOG)) {
            $this->_writeLog($log, $tags, self::BOX_LOG_PREFIX);
        }
    }

    /**
     *
     *
     * @param string $log Log message.
     *
     * @return void
     */
    public function logBoxOutputData($log, $tags = array())
    {
        if ($this->_isAllowed(self::BOX_DATA_LOG)) {
            $this->_writeLog($log, $tags, self::BOX_LOG_PREFIX);
        }
    }

    /**
     *
     *
     * @param string $log Log message.
     *
     * @return void
     */
    public function logBoxOperationLevel($log, $tags = array())
    {
        if ($this->_isAllowed(self::BOX_OPERATION_LOG)) {
            $this->_writeLog($log, $tags, self::BOX_OPERATION_LOG_PREFIX);
        }
    }

    /**
     *
     *
     * @param string $log Log message.
     *
     * @return void
     */
    public function logBoxOperationStatus($log, $tags = array())
    {
        if ($this->_isAllowed(self::BOX_OPERATION_LOG)) {
            $this->_writeLog($log, $tags, self::BOX_OPERATION_LOG_PREFIX);
        }
    }

    /**
     *
     *
     * @param string $log Log message.
     *
     * @return void
     */
    public function logBoxOperationError($log, $tags = array())
    {
        if ($this->_isAllowed(self::BOX_OPERATION_ERROR_LOG)) {
            $this->_writeLog($log, $tags, self::BOX_OPERATION_LOG_PREFIX);
        }
    }

    /**
     *
     *
     * @param string $log Log message.
     *
     * @return void
     */
    public function logBoxOperationWarning($log, $tags = array())
    {
        if ($this->_isAllowed(self::BOX_OPERATION_WARNING_LOG)) {
            $this->_writeLog($log, $tags, self::BOX_OPERATION_LOG_PREFIX);
        }
    }

    /**
     *
     *
     * @param string $log Log message.
     *
     * @return void
     */
    public function logBoxOperationInputData($log, $tags = array())
    {
        if ($this->_isAllowed(self::BOX_OPERATION_DATA_LOG)) {
            $this->_writeLog($log, $tags, self::BOX_OPERATION_LOG_PREFIX);
        }
    }

    /**
     *
     *
     * @param string $log Log message.
     *
     * @return void
     */
    public function logBoxOperationOutputData($log, $tags = array())
    {
        if ($this->_isAllowed(self::BOX_OPERATION_DATA_LOG)) {
            $this->_writeLog($log, $tags, self::BOX_OPERATION_LOG_PREFIX);
        }
    }

    /**
     *
     *
     * @param string $log Log message.
     *
     * @return void
     */
    public function logPostIterationLevel($log, $tags = array())
    {
        if ($this->_isAllowed(self::POST_ITERATION_LOG)) {
            $this->_writeLog($log, $tags, self::POST_ITERATION_LOG_PREFIX);
        }
    }

    /**
     *
     *
     * @param string $log Log message.
     *
     * @return void
     */
    public function logPostIterationStatus($log, $tags = array())
    {
        if ($this->_isAllowed(self::POST_ITERATION_LOG)) {
            $this->_writeLog($log, $tags, self::POST_ITERATION_LOG_PREFIX);
        }
    }

    /**
     *
     *
     * @param string $log Log message.
     *
     * @return void
     */
    public function logPostIterationError($log, $tags = array())
    {
        if ($this->_isAllowed(self::POST_ITERATION_ERROR_LOG)) {
            $this->_writeLog($log, $tags, self::POST_ITERATION_LOG_PREFIX);
        }
    }

    /**
     *
     *
     * @param string $log Log message.
     *
     * @return void
     */
    public function logPostIterationWarning($log, $tags = array())
    {
        if ($this->_isAllowed(self::POST_ITERATION_WARNING_LOG)) {
            $this->_writeLog($log, $tags, self::POST_ITERATION_LOG_PREFIX);
        }
    }

    /**
     *
     *
     * @param string $log Log message.
     *
     * @return void
     */
    public function logPostIterationInputData($log, $tags = array())
    {
        if ($this->_isAllowed(self::POST_ITERATION_DATA_LOG)) {
            $this->_writeLog($log, $tags, self::POST_ITERATION_LOG_PREFIX);
        }
    }

    /**
     *
     *
     * @param string $log Log message.
     *
     * @return void
     */
    public function logPostIterationOutputData($log, $tags = array())
    {
        if ($this->_isAllowed(self::POST_ITERATION_DATA_LOG)) {
            $this->_writeLog($log, $tags, self::POST_ITERATION_LOG_PREFIX);
        }
    }
}