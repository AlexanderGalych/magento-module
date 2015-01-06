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
 * Symmetrics Manager Adminhtml Worker Grid Block class.
 *
 * @category  Symmetrics
 * @package   Symmetrics_Manager
 * @author    symmetrics - a CGI Group brand <info@symmetrics.de>
 * @author    Alex Galych <aleksandr.galych@symmetrics.de>
 * @copyright 2014 symmetrics - a CGI Group brand
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
class Symmetrics_Manager_Block_Adminhtml_Worker_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Mass-action block.
     *
     * @var string
     */
    protected $_massactionBlockName = 'manager/adminhtml_worker_grid_massaction';

    /**
     * Class constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('manage_workers_grid');
        $this->_filterVisibility = false;
        $this->_pagerVisibility = true;
    }

    /**
     * Prepare grid collection.
     *
     * @return Mage_Index_Block_Adminhtml_Process_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('manager/worker')->getCollection();
        $this->setCollection($collection);
        parent::_prepareCollection();

        return $this;
    }

    /**
     * Add name and description to collection elements.
     *
     * @return Mage_Index_Block_Adminhtml_Process_Grid
     */
    protected function _afterLoadCollection()
    {
        return $this;
    }

    /**
     * Prepare grid columns.
     *
     * @return Mage_Index_Block_Adminhtml_Process_Grid
     */
    protected function _prepareColumns()
    {
        $dateFormat = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
        $this->addColumn(
            'entry_id', array(
                'header' => Mage::helper('manager')->__('Worker Id'),
                'width' => '180',
                'align' => 'left',
                'index' => 'entry_id',
                'sortable' => true,
            )
        )->addColumn(
            'pid', array(
                'header' => Mage::helper('manager')->__('Process Id'),
                'sortable' => true,
                'width' => '120',
                'align' => 'left',
                'index' => 'pid',
            )
        )->addColumn(
            'callback', array(
                'header' => Mage::helper('manager')->__('Callback'),
                'align' => 'left',
                'index' => 'callback',
                'sortable' => true,
            )
        )->addColumn(
            'description', array(
                'header' => Mage::helper('manager')->__('Description'),
                'align' => 'left',
                'index' => 'description',
                'sortable' => false,
            )
        )->addColumn(
            'status', array(
                'header' => Mage::helper('manager')->__('Status'),
                'width' => '120',
                'align' => 'left',
                'index' => 'status',
                'type' => 'options',
                'options' => 'status',
                'frame_callback' => array($this, 'decorateStatus')
            )
        )->addColumn(
            'creation_time', array(
                'header' => Mage::helper('manager')->__('Created At'),
                'type' => 'date',
                'format' => $dateFormat,
                'width' => '180',
                'align' => 'left',
                'index' => 'creation_time',
                'frame_callback' => array($this, 'decorateDate')
            )
        )->addColumn(
            'finished_time', array(
                'header' => Mage::helper('manager')->__('Finished At'),
                'type' => 'date',
                'format' => $dateFormat,
                'width' => '180',
                'align' => 'left',
                'index' => 'finished_time',
                'frame_callback' => array($this, 'decorateDate')
            )
        )->addColumn(
            'end_time', array(
                'header' => Mage::helper('manager')->__('End Date'),
                'type' => 'date',
                'format' => $dateFormat,
                'width' => '180',
                'align' => 'left',
                'index' => 'end_time',
                'frame_callback' => array($this, 'decorateDate')
            )
        )->addColumn(
            'action', array(
                'header' => Mage::helper('manager')->__('Action'),
                'width' => '100',
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('index')->__('Remove'),
                        'url' => array('base'=> '*/*/remove'),
                        'field' => 'worker'
                    ),
                ),
                'filter' => false,
                'sortable' => false,
                'is_system' => true,
            )
        );

        parent::_prepareColumns();
        return $this;
    }

    /**
     * Decorate status column values.
     *
     * @param string                          $value Status value.
     * @param Symmetrics_Manager_Model_Worker $row   Worker row.
     *
     * @return string
     */
    public function decorateStatus($value, $row)
    {
        $class = '';
        switch ($row->getStatus()) {
            case Symmetrics_Manager_Model_Worker::STATUS_FINISHED :
                $class = 'grid-severity-major';
                $value = Mage::helper('manager')->__('Finished');
                break;
            case Symmetrics_Manager_Model_Worker::STATUS_CREATED :
                $class = 'grid-severity-notice';
                $value = Mage::helper('manager')->__('Created');
                break;
            case Symmetrics_Manager_Model_Worker::STATUS_RUNNING :
                $class = 'grid-severity-notice';
                $value = Mage::helper('manager')->__('Running');
                break;
            case Symmetrics_Manager_Model_Worker::STATUS_STOPPED :
                $class = 'grid-severity-critical';
                $value = Mage::helper('manager')->__('Stopped');
                break;
            case Symmetrics_Manager_Model_Worker::STATUS_WAITING :
                $class = 'grid-severity-critical';
                $value = Mage::helper('manager')->__('Waiting');
                break;
        }
        return '<span class="'.$class.'"><span>'.$value.'</span></span>';
    }

    /**
     * Decorate last run date column.
     *
     * @param string $value Status value.
     *
     * @return string
     */
    public function decorateDate($value)
    {
        if (!$value) {
            return $this->__('Not Specified');
        }
        return $value;
    }

    /**
     * Get row edit url.
     *
     * @param Symmetrics_Manager_Model_Worker $row Row data.
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('worker' => $row->getId()));
    }

    /**
     * Add mass-actions to grid.
     *
     * @return Mage_Index_Block_Adminhtml_Process_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('worker_id');
        $this->getMassactionBlock()->setFormFieldName('worker');

        $this->getMassactionBlock()->addItem(
            'run', array(
                'label'    => Mage::helper('index')->__('Run'),
                'url'      => $this->getUrl('*/*/massStart'),
                'selected' => true,
            )
        );
        $this->getMassactionBlock()->addItem(
            'stop', array(
                'label'    => Mage::helper('index')->__('Stop'),
                'url'      => $this->getUrl('*/*/massStop'),
                'selected' => false,
            )
        );

        $this->getMassactionBlock()->addItem(
            'remove', array(
                'label'    => Mage::helper('index')->__('Remove'),
                'url'      => $this->getUrl('*/*/massRemove'),
                'selected' => false,
            )
        );
        return $this;
    }
}
