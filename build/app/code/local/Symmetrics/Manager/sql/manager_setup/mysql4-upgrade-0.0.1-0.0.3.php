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
 * Create new table "manager_worker_entry" for Workers Management.
 *
 * @category  Symmetrics
 * @package   Symmetrics_Manager
 * @author    symmetrics - a CGI Group brand <info@symmetrics.de>
 * @author    Alex Galych <aleksandr.galych@symmetrics.de>
 * @copyright 2014 symmetrics - a CGI Group brand
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
/** @var Symmetrics_Manage_Model_Setup $installer */
$installer = $this;

$installer->startSetup();
$unitTableName = 'manager/worker_entry';
if (!$installer->getConnection()
    ->isTableExists($installer->getTable($unitTableName))
) {
    $table = $installer->getConnection()
        ->newTable($installer->getTable($unitTableName));
    $table->addColumn(
        'entry_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
        ),
        'Entity Unit ID'
    );
    $table->addColumn(
        'pid',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned' => true,
            'nullable' => true,
        ),
        'Process ID'
    );
    $table->addColumn(
        'callback',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,
        32,
        array(
            'nullable' => false,
        ),
        'Callback function'
    );
    $table->addColumn(
        'description',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,
        255,
        array(
            'nullable' => true,
            'default' => null,
        ),
        'Description'
    );
    $table->addColumn(
        'log_level',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        255,
        array(
            'nullable' => false,
            'default' => 0,
        ),
        'Log Level'
    );
    $table->addColumn(
        'status',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        255,
        array(
            'unsigned' => true,
            'nullable' => false,
            'default' => 0,
        ),
        'Status'
    );
    $table->addColumn(
        'creation_time',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(
            'nullable' => false,
            'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT,
        ),
        'Date Create'
    );
    $table->addColumn(
        'finished_time',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(
            'nullable' => false,
            'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT,
        ),
        'Date Finished'
    );
    $table->addColumn(
        'end_time',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(
            'nullable' => true,
        ),
        'Kill date'
    );
    $table->setComment('Workers Manager Entity');
    $installer->getConnection()->createTable($table);
}
$installer->endSetup();