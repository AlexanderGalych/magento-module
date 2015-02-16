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
require_once('php-amqplib/vendor/autoload.php');

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Abstract class for Callback functions.
 *
 * @category  Symmetrics
 * @package   Symmetrics_Manager
 * @author    symmetrics - a CGI Group brand <info@symmetrics.de>
 * @author    Alex Galych <aleksandr.galych@symmetrics.de>
 * @copyright 2014 symmetrics - a CGI Group brand
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
abstract class Symmetrics_Manager_Model_Callback_Function_Abstract
{
    /**
     * Default exchange type.
     */
    const  EXCHANGE_TYPE = 'direct';
    /**
     * Default message type.
     */
    const MESSAGE_TYPE = 'text/plain';

    /**
     * @var AMQPConnection Rabbitmq connection.
     */
    protected $_connection;
    /**
     * @var PhpAmqpLib\Channel\AMQPChannel Rabbitmq channel.
     */
    protected $_channel;
    /**
     * @var string RabbitMQ queue.
     */
    protected $_queue;
    /**
     * @var string RabbitMQ exchange.
     */
    protected $_exchange;
    /**
     * @var resource Log file resource.
     */
    protected $_logFile;

    /**
     * Init object method.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->_exchange = Mage::getStoreConfig('rabbit_mq/message/exchange');
        $this->_queue = Mage::getStoreConfig('rabbit_mq/message/queue');
        $this->_establishConnection();

        $this->_logFile = fopen(Mage::getBaseDir('var') . "/workers/processor_" . getmypid() . "_log.log", "w");
        fwrite($this->_logFile, 'php processor pid: ' . getmypid()  . "\n\n");
    }

    /**
     * Destruct object properties.
     *
     * @return void
     */
    public function __destruct()
    {
        if ($this->_channel) {
            $this->_channel->close();
        }

        if ($this->_connection) {
            $this->_connection->close();
        }
        fclose($this->_logFile);
    }

    /**
     * Retrieves connection params.
     *
     * @return array
     */
    protected function _getConnectionConfig()
    {
        return Mage::getStoreConfig('rabbit_mq/connection');
    }

    /**
     * Establish AMPQ connection.
     *
     * @return void
     *
     * @throws Exception
     */
    protected function _establishConnection()
    {
        $config = $this->_getConnectionConfig();
        $this->_connection = new AMQPConnection(
            $config['host'], $config['port'], $config['user'], $config['password'], $config['vhost']
        );
        $this->_channel = $this->_connection->channel();

        if ($this->_channel) {
            $this->_channel->queue_declare($this->_queue, false, true, false, false);
            $this->_channel->exchange_declare($this->_exchange, self::EXCHANGE_TYPE, false, true, false);
            $this->_channel->queue_bind($this->_queue, $this->_exchange);
        } else {
            throw new Exception('Invalid Amqp server credential.');
        }
    }

    /**
     * Post message to AMPQ server.
     *
     * @param string $message Message text.
     *
     * @return void
     */
    protected function _publishMessage($message = '')
    {
        $amQpMsg = new AMQPMessage($message, array('content_type' => self::MESSAGE_TYPE, 'delivery_mode' => 2));
        $this->_channel->basic_publish($amQpMsg, $this->_exchange);
    }

    /**
     * Retrieve message from AMPQ server.
     *
     * @return string
     */
    protected function _retrieveMessage()
    {
        /** @var AMQPMessage $amQpMsg */
        $amQpMsg = $this->_channel->basic_get($this->_queue);
        if (is_object($amQpMsg)) {
            $objectKey = 'delivery_info';
            $this->_channel->basic_ack($amQpMsg->$objectKey['delivery_tag']);
        }
        return $amQpMsg->body;
    }
}