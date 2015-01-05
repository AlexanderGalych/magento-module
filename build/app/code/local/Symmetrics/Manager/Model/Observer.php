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
 * Symmetrics Management module observer class.
 *
 * @category  Symmetrics
 * @package   Symmetrics_Manager
 * @author    symmetrics - a CGI Group brand <info@symmetrics.de>
 * @author    Alex Galych <aleksandr.galych@symmetrics.de>
 * @copyright 2014 symmetrics - a CGI Group brand
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
class Symmetrics_Manager_Model_Observer extends Mage_Core_Model_Abstract
{
    /**
     * Manage workers scripts by cron.
     *
     * @throws Exception
     *
     * @return void
     */
    function manageWorkersScripts()
    {
        $workersCollection = Mage::getModel('manager/worker')->getCollection()->addFieldToSelect('*');
        foreach ($workersCollection as $worker) {
            $worker->manageWorkerState();
        }
    }

    /**
     * Manage worker status by after_save event.
     *
     * @param Varien_Event_Observer $observer Observer model.
     *
     * @return void
     */
    function managerWorkerAfterSave($observer)
    {
        $worker = $observer->getEvent()->getObject();
        $worker->manageWorkerState();
    }
}