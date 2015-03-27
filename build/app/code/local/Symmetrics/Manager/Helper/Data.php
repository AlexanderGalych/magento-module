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
 * Workers Management helper
 *
 * @category  Symmetrics
 * @package   Symmetrics_Manager
 * @author    symmetrics - a CGI Group brand <info@symmetrics.de>
 * @author    Alex Galych <aleksandr.galych@symmetrics.de>
 * @copyright 2014 symmetrics - a CGI Group brand
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
class Symmetrics_Manager_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * Get array of available worker's statuses.
     *
     * @return array.
     */
    public function getWorkerStatuses()
    {
        return array(
            Symmetrics_Manager_Model_Worker::STATUS_CREATED => $this->__('Created'),
            Symmetrics_Manager_Model_Worker::STATUS_STOPPED => $this->__('Stopped'),
            Symmetrics_Manager_Model_Worker::STATUS_FINISHED => $this->__('Finished'),
            Symmetrics_Manager_Model_Worker::STATUS_RUNNING => $this->__('Running'),
            Symmetrics_Manager_Model_Worker::STATUS_WAITING => $this->__('Waiting'),
        );
    }

    /**
     * Get array of available worker's callback functions.
     *
     * @return array.
     */
    public function getWorkerCallbackFunctions()
    {
        $callbackFunctions = Mage::getStoreConfig('callback_function');
        return $callbackFunctions;
    }

    /**
     * @return array
     */
    public function getLogLevels()
    {
        return Mage::getSingleton('manager/system_config_source_loglevel')->toArray();
    }

    /**
     * Array of available numbers of workers on add worker form.
     *
     * @return array
     */
    public function getArrayOfWorkersNumber()
    {
        return array_slice(range(0, 100), 1, null, true);
    }
}
