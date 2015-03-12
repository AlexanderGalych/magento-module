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
 * Used in creating options for Level Logging config value selection.
 *
 * @category  Symmetrics
 * @package   Symmetrics_Manager
 * @author    symmetrics - a CGI Group brand <info@symmetrics.de>
 * @author    Alex Galych <aleksandr.galych@symmetrics.de>
 * @copyright 2015 symmetrics - a CGI Group brand
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
class Symmetrics_Manager_Model_System_Config_Source_Loglevel
{
    /**
     * Options getter.
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 1, 'label'=>Mage::helper('manager')->__('OFF')),
            array('value' => 2, 'label'=>Mage::helper('manager')->__('FATAL')),
            array('value' => 3, 'label'=>Mage::helper('manager')->__('ERROR')),
            array('value' => 4, 'label'=>Mage::helper('manager')->__('WARNING')),
            array('value' => 5, 'label'=>Mage::helper('manager')->__('INFO')),
            array('value' => 6, 'label'=>Mage::helper('manager')->__('DEBUG')),
            array('value' => 7, 'label'=>Mage::helper('manager')->__('ALL')),
        );
    }

    /**
     * Get options in "key-value" format.
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            0 => Mage::helper('manager')->__('FOLLOW BASE CONFIGURATION'),
            1 => Mage::helper('manager')->__('OFF'),
            2 => Mage::helper('manager')->__('FATAL'),
            3 => Mage::helper('manager')->__('ERROR'),
            4 => Mage::helper('manager')->__('WARNING'),
            5 => Mage::helper('manager')->__('INFO'),
            6 => Mage::helper('manager')->__('DEBUG'),
            7 => Mage::helper('manager')->__('ALL'),
        );
    }
}
