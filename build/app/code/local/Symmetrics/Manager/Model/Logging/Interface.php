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
    public function logFatalError($log);

    public function logInfoMessage($log);


    public function logGlobalLevel($log);

    public function logGlobalStatus($log);

    public function logGlobalError($log);

    public function logGlobalWarning($log);

    public function logGlobalInputData($log);

    public function logGlobalOutputData($log);


    public function logIterationLevel($log);

    public function logIterationStatus($log);

    public function logIterationError($log);

    public function logIterationWarning($log);

    public function logIterationInputData($log);

    public function logIterationOutputData($log);


    public function logBoxLevel($log);

    public function logBoxStatus($log);

    public function logBoxError($log);

    public function logBoxWarning($log);

    public function logBoxInputData($log);

    public function logBoxOutputData($log);


    public function logBoxOperationLevel($log);

    public function logBoxOperationStatus($log);

    public function logBoxOperationError($log);

    public function logBoxOperationWarning($log);

    public function logBoxOperationInputData($log);

    public function logBoxOperationOutputData($log);


    public function logPostIterationLevel($log);

    public function logPostIterationStatus($log);

    public function logPostIterationError($log);

    public function logPostIterationWarning($log);

    public function logPostIterationInputData($log);

    public function logPostIterationOutputData($log);
}