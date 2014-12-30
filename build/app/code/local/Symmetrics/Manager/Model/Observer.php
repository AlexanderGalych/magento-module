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
        $workersNumber = (int) Mage::getStoreConfig('manager/general/workers_number');
        $workersCollection = Mage::getModel('manager/worker')->getCollection();
        $delta = $workersCollection->getSize() - $workersNumber;

        foreach ($workersCollection as $worker) {
            $worker->manageWorkerState();
        }
        if ($delta) {
            Mage::helper('manager')->manageWorkersQuantity($delta);
        }
    }
}