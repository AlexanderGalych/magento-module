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
 * Interface class for Workers Logs.
 *
 * @category  Symmetrics
 * @package   Symmetrics_Manager
 * @author    symmetrics - a CGI Group brand <info@symmetrics.de>
 * @author    Alex Galych <aleksandr.galych@symmetrics.de>
 * @copyright 2015 symmetrics - a CGI Group brand
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
interface Symmetrics_Manager_Model_Logging_Interface
{
    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * 
     * @return mixed
     */
    public function logFatalError($log, $tags);

    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * 
     * @return mixed
     */
    public function logInfoMessage($log, $tags);

    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * 
     * @return mixed
     */
    public function logGlobalLevel($log, $tags);

    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * 
     * @return mixed
     */
    public function logGlobalStatus($log, $tags);

    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * 
     * @return mixed
     */
    public function logGlobalError($log, $tags);

    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * 
     * @return mixed
     */
    public function logGlobalWarning($log, $tags);

    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * 
     * @return mixed
     */
    public function logGlobalInputData($log, $tags);

    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * 
     * @return mixed
     */
    public function logGlobalOutputData($log, $tags);

    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * 
     * @return mixed
     */
    public function logIterationLevel($log, $tags);

    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * 
     * @return mixed
     */
    public function logIterationStatus($log, $tags);

    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * 
     * @return mixed
     */
    public function logIterationError($log, $tags);

    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * 
     * @return mixed
     */
    public function logIterationWarning($log, $tags);

    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * 
     * @return mixed
     */
    public function logIterationInputData($log, $tags);

    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * 
     * @return mixed
     */
    public function logIterationOutputData($log, $tags);

    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * 
     * @return mixed
     */
    public function logBoxLevel($log, $tags);

    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * 
     * @return mixed
     */
    public function logBoxStatus($log, $tags);

    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * 
     * @return mixed
     */
    public function logBoxError($log, $tags);

    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * 
     * @return mixed
     */
    public function logBoxWarning($log, $tags);

    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * 
     * @return mixed
     */
    public function logBoxInputData($log, $tags);

    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * 
     * @return mixed
     */
    public function logBoxOutputData($log, $tags);

    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * 
     * @return mixed
     */
    public function logBoxOperationLevel($log, $tags);

    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * 
     * @return mixed
     */
    public function logBoxOperationStatus($log, $tags);

    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * 
     * @return mixed
     */
    public function logBoxOperationError($log, $tags);

    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * 
     * @return mixed
     */
    public function logBoxOperationWarning($log, $tags);

    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * @return mixed
     */
    public function logBoxOperationInputData($log, $tags);

    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * 
     * @return mixed
     */
    public function logBoxOperationOutputData($log, $tags);

    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * 
     * @return mixed
     */
    public function logPostIterationLevel($log, $tags);

    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * 
     * @return mixed
     */
    public function logPostIterationStatus($log, $tags);

    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * 
     * @return mixed
     */
    public function logPostIterationError($log, $tags);

    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * @return mixed
     */
    public function logPostIterationWarning($log, $tags);

    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * 
     * @return mixed
     */
    public function logPostIterationInputData($log, $tags);

    /**
     *
     *
     * @param string $log Log string.
     * @param array  $tags Array of log tags.
     * 
     * @return mixed
     */
    public function logPostIterationOutputData($log, $tags);
}