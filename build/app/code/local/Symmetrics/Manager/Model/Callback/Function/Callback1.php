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
 * Callback1 function class.
 *
 * @category  Symmetrics
 * @package   Symmetrics_Manager
 * @author    symmetrics - a CGI Group brand <info@symmetrics.de>
 * @author    Alex Galych <aleksandr.galych@symmetrics.de>
 * @copyright 2014 symmetrics - a CGI Group brand
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
class Symmetrics_Manager_Model_Callback_Function_Callback1
    implements Symmetrics_Manager_Model_Callback_Function_Interface
{
    /**
     * Execute callback1 function.
     */
    public function execute()
    {
        $myFile = fopen("/var/www/demo_ce_1411/build/var/workers/processor_" . getmypid() . "_log.txt", "w");
        $urlPublish = 'http://rabbitmq.api.local/api/v1/publish';

        fwrite($myFile, 'php processor pid: ' . getmypid()  . "\n\n");

        while (1) {
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL, $urlPublish);
            curl_setopt($ch,CURLOPT_POST, 1);
            curl_setopt($ch,CURLOPT_POSTFIELDS, 'message=message test ' . date('d-m-Y H:i:s')  . "\n");
            $result = curl_exec($ch);
            fwrite($myFile, 'Publish message: "message test ' . date('d-m-Y H:i:s')  . "\n");
            sleep(mt_rand(1, 10));
        }
        fclose($myFile);
        return true;
    }
}