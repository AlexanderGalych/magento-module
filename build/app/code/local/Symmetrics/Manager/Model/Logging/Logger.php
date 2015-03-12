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
 * Worker Callback Logger model.
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

    public function logFatalError($log)
    {
        $this->_writeLog($log);
    }

    public function logInfoMessage($log)
    {
        $this->_writeLog($log);
    }

    public function logGlobalLevel($log)
    {
        $this->_writeLog($log);
    }

    public function logGlobalStatus($log)
    {
        $this->_writeLog($log);
    }

    public function logGlobalError($log)
    {
        $this->_writeLog($log);
    }

    public function logGlobalWarning($log)
    {
        $this->_writeLog($log);
    }

    public function logGlobalInputData($log)
    {
        $this->_writeLog($log);
    }

    public function logGlobalOutputData($log)
    {
        $this->_writeLog($log);
    }

    public function logIterationLevel($log)
    {
        $this->_writeLog($log);
    }

    public function logIterationStatus($log)
    {
        $this->_writeLog($log);
    }

    public function logIterationError($log)
    {
        $this->_writeLog($log);
    }

    public function logIterationWarning($log)
    {
        $this->_writeLog($log);
    }

    public function logIterationInputData($log)
    {
        $this->_writeLog($log);
    }

    public function logIterationOutputData($log)
    {
        $this->_writeLog($log);
    }

    public function logBoxLevel($log)
    {
        $this->_writeLog($log);
    }

    public function logBoxStatus($log)
    {
        $this->_writeLog($log);
    }

    public function logBoxError($log)
    {
        $this->_writeLog($log);
    }

    public function logBoxWarning($log)
    {
        $this->_writeLog($log);
    }

    public function logBoxInputData($log)
    {
        $this->_writeLog($log);
    }

    public function logBoxOutputData($log)
    {
        $this->_writeLog($log);
    }

    public function logBoxOperationLevel($log)
    {
        $this->_writeLog($log);
    }

    public function logBoxOperationStatus($log)
    {
        $this->_writeLog($log);
    }

    public function logBoxOperationError($log)
    {
        $this->_writeLog($log);
    }

    public function logBoxOperationWarning($log)
    {
        $this->_writeLog($log);
    }

    public function logBoxOperationInputData($log)
    {
        $this->_writeLog($log);
    }

    public function logBoxOperationOutputData($log)
    {
        $this->_writeLog($log);
    }

    public function logPostIterationLevel($log)
    {
        $this->_writeLog($log);
    }

    public function logPostIterationStatus($log)
    {
        $this->_writeLog($log);
    }

    public function logPostIterationError($log)
    {
        $this->_writeLog($log);
    }

    public function logPostIterationWarning($log)
    {
        $this->_writeLog($log);
    }

    public function logPostIterationInputData($log)
    {
        $this->_writeLog($log);
    }

    public function logPostIterationOutputData($log)
    {
        $this->_writeLog($log);
    }
}