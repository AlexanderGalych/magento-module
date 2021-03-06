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
 * Callback2 function class.
 *
 * @category  Symmetrics
 * @package   Symmetrics_Manager
 * @author    symmetrics - a CGI Group brand <info@symmetrics.de>
 * @author    Alex Galych <aleksandr.galych@symmetrics.de>
 * @copyright 2014 symmetrics - a CGI Group brand
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
class Symmetrics_Manager_Model_Callback_Function_Callback2
    extends Symmetrics_Manager_Model_Callback_Function_Abstract
    implements Symmetrics_Manager_Model_Callback_Function_Interface
{

    /**
     * Execute callback2 function (curl).
     *
     * @return null
     *
     * @throws Exception
     */
    public function execute()
    {
        while (1) {
            try {
                $message = $this->_retrieveMessage();
                if ($message) {
                    $this->getLogger()->logInfoMessage("Retrieved message: " . $message, array('RMQ', 'RECEIVED'));
                }
            } catch (PhpAmqpLib\Exception\AMQPProtocolException $e) {
                $this->getLogger()->logFatalError($e, array('AMPQ', 'Connection'));
            } catch (Exception $e) {
                $this->getLogger()->logFatalError($e, array('UNKNOWN', 'Connection'));
            } finally {
                sleep(mt_rand(1, 10));
            }
        }
    }
}