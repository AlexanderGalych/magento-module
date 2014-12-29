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
 * @package   Symmetrics_Setup
 * @author    symmetrics gmbh <info@symmetrics.de>
 * @author    Alexey Kovtunets <alexey.kovtunets@symmetrics.de>
 * @copyright 2011 symmetrics gmbh
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */

/**
 * Symmetrics Setup class.
 *
 * @SuppressWarnings(PHPMD)
 *
 * @category  Symmetrics
 * @package   Symmetrics_Setup
 * @author    symmetrics gmbh <info@symmetrics.de>
 * @author    Alexey Kovtunets <alexey.kovtunets@symmetrics.de>
 * @author    Eduard Melnitskiy <eduard.melnitskiy@symmetrics.de>
 * @copyright 2011 symmetrics gmbh
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
class Symmetrics_Setup_Model_Setup extends Mage_Catalog_Model_Resource_Eav_Mysql4_Setup
{
    /**
     * Add static page to the system.
     *
     * @param array $data Data for cms page to insert.
     *
     * @return null
     */
    public function addCmsPage($data)
    {
        try {
            if (!empty($data['identifier'])) {
                /* Check first that page is not exist in the system (by identifier). */
                $pagesCollection = Mage::getModel('cms/page')->getCollection();
                $pagesCollection->addFieldToFilter('identifier', $data['identifier'])
                    ->addStoreFilter(array_merge(array('0'), $data['stores']));

                if ($pagesCollection->getSize() > 0) {
                    foreach ($pagesCollection as $page) {
                        $page->delete();
                    }
                }
                $pageModel = Mage::getModel('cms/page');

                $pageModel->setData($data);
                $pageModel->save();
            }
        } catch (Symmetrics_Setup_InstallException $e) {

        } catch (Exception $e) {
            throw $e;
        }
        return $pageModel;
    }

    /**
     * Add static block to the system.
     *
     * @param array $data Data for cms block to insert.
     *
     * @return false|int
     */
    public function addCmsBlock($data)
    {
        try {
            if (!empty($data['identifier'])) {
                /* Check first that block is not exist in the system (by identifier and store). */
                $blocksCollection = Mage::getModel('cms/block')->getCollection();
                $blocksCollection->addFieldToFilter('identifier', $data['identifier'])
                    ->addStoreFilter(array_merge(array('0'), $data['stores']));
                if ($blocksCollection->getSize() > 0) {
                    foreach ($blocksCollection as $block) {
                        $block->delete();
                    }
                }
                $blockModel = Mage::getModel('cms/block');
                $blockModel->setData($data);
                $blockModel->save();
                return $blockModel->getId();
            }
        } catch (Symmetrics_Setup_InstallException $e) {

        } catch (Exception $e) {
            throw $e;
        }
        return false;
    }

    /**
     * Fucntion creates website, store group and store by given parmeters.
     *
     * @param string $name    Name of website.
     * @param string $code    Two-digits code for shop.
     * @param string $country Two-digits code of country.
     * @param string $locale  Locale for website.
     *
     * @return null
     */
    public function createStore($name, $code, $country, $locale)
    {
        $name = ucfirst($name);
        static $rootCategoryId, $unsecureBaseUrl, $secureBaseUrl, $dir, $config, $indexTemplate;
        if (is_null($rootCategoryId)) {
            Mage::getConfig()->loadDb();
            $rootCategoryId = Mage::app()
                ->getWebsite(1)
                ->getDefaultStore()
                ->getRootCategoryId();
            $unsecureBaseUrl = Mage::getStoreConfig('web/unsecure/base_url');
            $secureBaseUrl = Mage::getStoreConfig('web/secure/base_url');
            $config = Mage::getModel('core/config');
            $dir = getcwd() . '/';
            $indexTemplate = dirname(__FILE__) . '/indexTemplate.php';
        }

        /* Website information */
        $websiteCode = 'website_' . $code;
        $websiteData = array(
            'name' => $name,//.' Website',
            'code' => $websiteCode,
            'sort_order' => '2',
            'is_active' => 1,
        );

        /* Save website */
        $websiteId = Mage::getModel('core/website')
            ->setData($websiteData)
            ->save()
            ->getId();
        //Mage::log("Website ".ucfirst($websiteData['name']).'( '.$websiteData['code'].') created: '.$websiteId);

        /* StoreGroup information */
        $storeGroupData = array(
            'root_category_id' => $rootCategoryId,
            'website_id' => $websiteId,
            'name' => $name . ' Store'
        );

        /* Save StoreGroup */
        $storeGroupId = Mage::getModel('core/store_group')
            ->setData($storeGroupData)
            ->save()
            ->getId();
        //Mage::log("Store group ".ucfirst($storeGroupData['name']).' created: '.$storeGroupId);

        /* Store information */
        $storeData = array(
            'code' => 'store_' . $code,
            'website_id' => $websiteId,
            'group_id' => $storeGroupId,
            'name' => $name . ' Store View',
            'is_active' => 1
        );
        $storeId = Mage::getModel('core/store')
            ->setData($storeData)
            ->save()
            ->getId();
        //Mage::log("Store " . ucfirst($storeData['name']) . '( ' .$storeData['code'].') created: '.$storeId);

        $config->saveConfig('general/country/default', $country, 'websites', $websiteId);
        $config->saveConfig('general/country/allow', $country, 'websites', $websiteId);
        $config->saveConfig('general/locale/code', $locale, 'websites', $websiteId);
        $config->saveConfig('web/unsecure/base_url', $unsecureBaseUrl . $code . '/', 'websites', $websiteId);
        $config->saveConfig('web/unsecure/base_link_url', $unsecureBaseUrl . $code . '/', 'websites', $websiteId);
        $config->saveConfig('web/secure/base_url', $secureBaseUrl . $code . '/', 'websites', $websiteId);
        $config->saveConfig('web/secure/base_link_url', $secureBaseUrl . $code .'/', 'websites', $websiteId);

        $targetDir = $dir . $code . '/';
        if (!is_dir($targetDir)) mkdir($targetDir);
        copy($dir . '.htaccess', $targetDir . '.htaccess'); 
        copy($indexTemplate, $targetDir . 'index.php');
        $cwd = getcwd();
        chdir($targetDir);
        symlink('../skin', 'skin');
        symlink('../js', 'js');
        chdir($cwd);
    }

    /**
     * Function adds rules for the given role.
     *
     * @param int    $roleId     Role Id.
     * @param string $resource   Resource.
     * @param bool   $permission If true sets resource permissions to 
     * 'allow'.
     *
     * @return int   New rule id.
     */
    public function setResourceAccess($roleId, $resource, $permission = false)
    {
        static $model;
        if (is_null($model)) $model = Mage::getModel('admin/rules');

        $data = array(
            'role_id' => $roleId,
            'resource_id' => $resource,
            'assert_id' => 0,
            'role_type' => 'G',
            'permission' => $permission ? 'allow' : 'deny'
        );
        $rule = $model->setData($data)->save();
        return $rule->getId();
    }

    /**
     * Create a new attribute set using "Default" set as skeleton.
     *
     * @param string $name Attribute set name.
     *
     * @return int Attribute set new created ID
     */
    public function addAttributeSetExtended($name)
    {
        $entityType = $this->getEntityType('catalog_product', 'entity_type_id');
        $model  = Mage::getModel('eav/entity_attribute_set')
            ->setEntityTypeId($entityType);
        $model->setAttributeSetName($name);
        $model->validate();
        $model->save();
        $model->initFromSkeleton($entityType);
        $model->save();
        return $model->getId();
    }

    /**
     * Returns store code -> store mapping.
     *
     * @return array
     */
    protected function _getStoreIds()
    {
        $storeCollection = Mage::getModel('core/store')->getCollection();
        $storeCollection->addFieldToFilter('code', array('neq' => 'admin'));
        $result = array();
        foreach ($storeCollection as $store) {
            $result[$store->getCode()] = $store;
        }
        return $result;
    }

    /**
     * Adds Cms blocks for all top categories and enables them.
     *
     * @return void
     */
    public function addCmsBlocksForCategories()
    {
        // HACK: disables update mode to enable a possibility to save per store category values.
        $updateMode = Mage::app()->getUpdateMode();
        Mage::app()->setUpdateMode(0);
        
        $stores = $this->_getStoreIds();
        /**
         * Sets current store, we need this because we disabled UpdateMode.
         */
        Mage::app()->setCurrentStore($stores['default']);

        $category = Mage::getModel('catalog/category');

        // 2 - Default category scanned for subcategories.
        $categories = $category->getCategories(2, 1, false, true, false);

        foreach ($categories as $category) {
            foreach ($stores as $lang => $store) {
                // Forms CMS block id.
                $cmsId = $category->getUrlKey() . '_' . $lang;
                $data = array(
                    'identifier' => $cmsId,
                    'stores' => array($store->getId()),
                    'is_active' => 1,
                    'title' => $category->getName() . ' - ' . strtoupper($lang),
                    'content' => '<img alt="' . $lang . '" src="{{media url="subhome-banner.jpg"}}"/>',
                );
                $blockId = $this->addCmsBlock($data);

                // If it is not DE store - sets store id.
                if ($stores['default'] != $store) {
                    $category->setStoreId($store->getId());
                }
                // Saves category.
                $category->setLandingPage($blockId)->setDisplayMode(Mage_Catalog_Model_Category::DM_MIXED)->save();
                // If it is DE store - saves additionally a default values.
                if ($stores['default'] == $store) {
                    // Sets default display mode value.
                    $category->setStoreId(0)->setLandingPage(0)
                        ->setDisplayMode(Mage_Catalog_Model_Category::DM_MIXED)->save();
                }
            }
        }
        Mage::app()->setUpdateMode($updateMode);
        // reindex
        $indexer = Mage::getSingleton('index/indexer');
        $process = $indexer->getProcessByCode('catalog_category_flat');
        $process->reindexEverything();
    }

    /**
     * Updates CMS page by given identifier. $params container has keys like table
     * fields, in other words: Varien_Object::$_data
     *
     * @param string|array $identifier The identifier of CMS page to update
     * @param array        $params     Parameters to set to
     * @param array        $storeIds   Ids of stores to apply CMS with
     *
     * @return void
     */
    public function updateCmsPage($identifier, array $params, array $storeIds = array())
    {
        if (is_array($identifier)) {
            foreach ($identifier as $_identifier) {
                $this->updateCmsPage($_identifier, $params, $storeIds);
            }
        }

        $pageModel = Mage::getModel('cms/page');
        /* @var $pageModel Mage_Cms_Model_Page */

        if (is_numeric($identifier)) {
            $pageModel->load($identifier);
        } else {
            $pageModel->load($identifier, 'identifier');
        }

        $pageModel->addData($params);

        // This is important for internal process of Mage_Cms_Model_Page::save().
        // 'stores' attribute has to be set before saving model else store referencing
        // get lost!!
        if ($storeIds) {
            $pageModel->setStores($storeIds);
        } else {
            $pageModel->setStores($pageModel->getStoreId());
        }
        $pageModel->save();

        return;
    }
    
     /**
     * Remove CMS Block by given identifier.
     * 
     * @param string $identifier Cms Block identifier.
     *
     * @return void
     */
    public function removeCmsBlock($identifier)
    {
        if (!empty($identifier)) {
            $blocksCollection = Mage::getModel('cms/block')->getCollection();
            $blocksCollection->addFieldToFilter('identifier', $identifier);
            if ($blocksCollection->getSize() > 0) {
                foreach ($blocksCollection as $block) {
                    $block->delete();
                }
            }
        }
    }

    /**
     * Collect data and update agreement.
     *
     * @param array $data agreement data.
     *
     * @return void
     */
    public function updateAgreement($data)
    {
        $agreement = Mage::getModel('checkout/agreement');
        $agreement->load($data['name'], 'name');
        if (!array_key_exists('stores', $data)) {
            $data['stores'] = array('0');
        }
        foreach ($data as $key => $value) {
            $agreement->setData($key, $value);
        }
        $agreement->save();
    }

     /**
     * Update transactional email template.
     *
     * @param array $emailData template data.
     *
     * @return void
     */
    public function updateEmailTemplate($emailData)
    {
        $template = Mage::getModel('core/email_template');
        $template->loadByCode($emailData['template_code']);
        foreach ($emailData as $code => $content) {
            $template->setData($code, $content);
        }
        $template->save();
    }
    
    /**
     * Method to create a cms hierarchy by data array.
     * Data array format:
     * array(
     *     'nodes_data' => array(
     *         '_0' => array(                 // Add an underscore for new entries.
     *             'node_id' => '_0',         // Node id.
     *             'parent_node_id' => null,  // Set to null if is parent.
     *             'page_id' => null,         // CMS block id.
     *             'label' => 'Service',      // Object label.
     *             'identifier' => 'service', // Url path.
     *             'level' => '1',            // '1' if is parent.
     *             'sort_order' => '0'        // Current level sort order.
     *         ),
     *         ...
     *     )
     * );
     *
     * @param array $data CMS hierarchie data as array.
     *
     * @return boolean
     */
    public function createCmsHierarchie($data)
    {
        /* @var $node Enterprise_Cms_Model_Hierarchy_Node */
        $node = Mage::getModel('enterprise_cms/hierarchy_node');

        try {
            $nodesData = array();
            $removedNodes = array();
            if (!empty($data['nodes_data'])) {
                $nodesData = $data['nodes_data'];
            }
            if (!empty($data['removed_nodes'])) {
                $removedNodes = $data['removed_nodes'];
            }

            $node->collectTree($nodesData, $removedNodes);

            return true;
        } catch (Exception $exception) {
            Mage::logException($exception);
        }

        return false;
    }
    
    /**
     * Update cms block.
     *
     * @param string $identifier Identifier for cms block.
     * @param string $title      Title for cms block.
     * @param string $content    Content for cms block, default is empty.
     *
     * @return void
     */
    public function updateCmsBlock($identifier, $title=false, $content='')
    {
        $block = Mage::getModel('cms/block')
            ->getCollection()
            ->getItemByColumnValue('identifier', $identifier);
        if ($block) {
            $block = $block->load($block->getId());
            if ($title !== false) {
                $block->setTitle($title);
            }
            $block->setContent($content);
            $block->setStores($block->getStoreId());
            $block->save();
        //Create a cms block if the block doesn't exist.
        } elseif ($title) {
            $this->addCmsBlock(
                array(
                    'identifier' => $identifier,
                    'title' => $title, 
                    'content' => $content,
                    'stores' => array(0)
                )
            );
        }
    }

    /**
     * Create CMS Block from html files in local folder.
     *
     * @param array  $blocks     Array of block prameters
     *                           key:   file name (without ".html") and block identifier
     *                           value: block title
     * @param string $pathPrefix Prefix for locale template path
     *
     * @return void
     */
    public function createCMSBlockFromLocalHtml($blocks, $pathPrefix = '')
    {
        $templatePath = Mage::getBaseDir('locale') . DS . $pathPrefix;

        foreach ($blocks as $identifier => $title) {
            $templateFile = $templatePath . $identifier . '.html';
            if (file_exists($templateFile)) {
                $content = file_get_contents($templateFile);
                $this->updateCmsBlock($identifier, $title, $content);
            } else {
                Mage::log($templateFile . ' file not exist.');
            }
        }

        return;
    }
    
    /**
     * Returns query for adding possibility to vote for all ratings such as price, quality, value 
     * in order to vote in all stores.
     * 
     * @return string
     */
    public function getAllRatingsForVoteQuery() 
    {
        $query = "REPLACE INTO {$this->getTable('rating_store')} (`rating_id`,`store_id`) VALUES ";
        $ratings = Mage::getModel('rating/rating')->getCollection();
        $ratingsLastItem = $ratings->getLastItem();

        foreach ($ratings as $rating) {
            $query.= "({$rating->getId()}, 0), ";
            foreach ($stores = $this->_getStoresIds() as $store) {
                if ((end($stores) == $store) && ($ratingsLastItem == $rating)) {
                    $query.= "({$rating->getId()}, {$store->getId()})";
                } else {
                    $query.= "({$rating->getId()}, {$store->getId()}), ";
                }
            }
        }
        return $query.= ";";
    }
}

