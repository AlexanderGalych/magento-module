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
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "../abstract.php";

/**
 * Worker main shell class.
 *
 * @category  Symmetrics
 * @package   Symmetrics_Manager
 * @author    symmetrics - a CGI Group brand <info@symmetrics.de>
 * @author    Alex Galych <aleksandr.galych@symmetrics.de>
 * @copyright 2014 symmetrics - a CGI Group brand
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
class Symmetrics_Manager_Worker extends Mage_Shell_Abstract
{
    /**
     * Path to callback function xml.
     *
     * @var null
     */
    protected $_callbackXmlPath = null;

    /**
     * Constructor for the shell script. Initialize arguments and basic variables.
     */
    public function __construct()
    {
        parent::__construct();

        if ($this->getArg('callback')) {
            $this->_callbackXmlPath = $this->getArg('callback');
        } else {
            echo "Callback argument should be defined.\n";
            exit;
        }
    }

    /**
     * Get Callback function instance.
     *
     * @return Symmetrics_Manager_Model_Callback_Function_Interface
     */
    protected function _getCallbackFunction()
    {
        $path = Symmetrics_Manager_Model_Callback_Base::XML_PATH_CALLBACK_FUNCTIONS . '/' . $this->_callbackXmlPath;
        return $this->_factory->getSingleton($this->_factory->getConfig()->getNode($path));
    }

    /**
     * Run script.
     *
     * return void.
     */
    public function run()
    {
        /** @var Symmetrics_Manager_Model_Callback_Function_Interface $callback */
        $callback = $this->_getCallbackFunction();
        if (is_object($callback)) {
            $callback->execute();
        } else {
            echo "Wrong callback model argument.\n";
        }
    }
}

$worker = new Symmetrics_Manager_Worker();
$worker->run();