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
 * Symmetrics Manager Adminhtml Worker Edit Tab Main block class.
 *
 * @category  Symmetrics
 * @package   Symmetrics_Manager
 * @author    symmetrics - a CGI Group brand <info@symmetrics.de>
 * @author    Alex Galych <aleksandr.galych@symmetrics.de>
 * @copyright 2014 symmetrics - a CGI Group brand
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
class Symmetrics_Manager_Block_Adminhtml_Worker_Edit_Tab_Main
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Prepare form for worker's editing.
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        /** @var Symmetrics_Manager_Model_Worker $model */
        $model = Mage::registry('current_worker');
        $dateFormat = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('manage_worker_');
        $fieldSet = $form->addFieldset(
            'base_fieldset',
            array('legend'=>Mage::helper('manager')->__('General'), 'class'=>'fieldset-wide')
        );

        $fieldSet->addField('worker', 'hidden', array('name' => 'worker', 'value' => $model->getId()));

        $fieldSet->addField(
            'name', 'note', array(
                'label' => Mage::helper('manager')->__('Process Id'),
                'title' => Mage::helper('manager')->__('Process Id'),
                'text'  => '<strong>'. $model->getPid() .'</strong>',
            )
        );

        $fieldSet->addField(
            'description', 'text',
            array(
                'label' => Mage::helper('manager')->__('Worker Description'),
                'title' => Mage::helper('manager')->__('Worker Description'),
                'name'  => 'description',
                'value' => $model->getDescription()
            )
        );

        $fieldSet->addField(
            'callback', 'select',
            array(
                'label' => Mage::helper('manager')->__('Callback'),
                'title' => Mage::helper('manager')->__('Callback'),
                'name'  => 'callback',
                'value' => $model->getCallback(),
                'values'=> Mage::helper('manager')->getWorkerCallbackFunctions()
            )
        );

        $fieldSet->addField(
            'status', 'select',
            array(
                'label' => Mage::helper('manager')->__('Status'),
                'title' => Mage::helper('manager')->__('Status'),
                'name'  => 'status',
                'value' => $model->getStatus(),
                'values'=> Mage::helper('manager')->getWorkerStatuses()
            )
        );

        $fieldSet->addField(
            'cron_expr', 'text',
            array(
                'label' => Mage::helper('manager')->__('Cron Expression'),
                'title' => Mage::helper('manager')->__('Cron Expression'),
                'name'  => 'cron_expr',
                'value' => $model->getCronExpr(),
            )
        );

        $fieldSet->addField(
            'log_level', 'select',
            array(
                'label' => Mage::helper('manager')->__('Log Level'),
                'title' => Mage::helper('manager')->__('Log Level'),
                'name'  => 'log_level',
                'value' => $model->getLogLevel(),
                'values'=> Mage::helper('manager')->getLogLevels()
            )
        );

        $fieldSet->addField(
            'is_use_end_time', 'checkbox',
            array(
                'label' => Mage::helper('manager')->__('Use Stop Date'),
                'title' => Mage::helper('manager')->__('Use Stop Date'),
                'name'  => 'is_use_end_time',
                'checked' => false,
                'onchange' => 'document.getElementById(\'manage_worker_end_time\').disabled = !this.checked',
                'value'  => '1',
                'align' => 'center',
            )
        );

        $fieldSet->addField(
            'end_time', 'date',
            array(
                'label' => Mage::helper('manager')->__('Stop date'),
                'title' => Mage::helper('manager')->__('Stop date'),
                'name'  => 'end_time',
                'value' => Mage::app()->getLocale()
                    ->date($model->getEndTime(), null, null, true)->toString($dateFormat),
                'format' => $dateFormat,
                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'time' => true,
                'after_element_html' =>
                    '<script>document.getElementById(\'manage_worker_end_time\').disabled = true;</script>'
            )
        );

        if (is_null($model->getEntityId())) {
            $fieldSet->addField(
                'number_of_workers', 'select',
                array(
                    'label' => Mage::helper('manager')->__('Number of workers'),
                    'title' => Mage::helper('manager')->__('Number of workers'),
                    'name'  => 'number_of_workers',
                    'value' =>1,
                    'values'=> Mage::helper('manager')->getArrayOfWorkersNumber(),
                )
            );
        }

//        Mage::getModel('manager/observer')->manageWorkersScripts();

        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab.
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('manager')->__('Worker Information');
    }

    /**
     * Prepare title for tab.
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('manager')->__('Worker Information');
    }

    /**
     * Returns status flag about this tab can be shown or not.
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not.
     *
     * @return true
     */
    public function isHidden()
    {
        return false;
    }
}
