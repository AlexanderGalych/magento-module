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
 * Symmetrics Management Module setup class.
 *
 * @category  Symmetrics
 * @package   Symmetrics_Manager
 * @author    symmetrics - a CGI Group brand <info@symmetrics.de>
 * @author    Alex Galych <aleksandr.galych@symmetrics.de>
 * @copyright 2014 symmetrics - a CGI Group brand
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
class Symmetrics_Manager_Model_Setup extends Mage_Catalog_Model_Resource_Eav_Mysql4_Setup
{
    /**
     * Add category and all its children recursively.
     * Data should have following structure:
     * {
     *   "name":"Name 1",  <-- name is required for each category.
     *   "children":[
     *     {
     *       "name":"Name 2",
     *       "is_active":"0",
     *       "children":[...]
     *     },
     *   ]
     * }
     *
     * @param array                       $categoryData   Array containing tree data.
     * @param Mage_Catalog_Model_Category $parentCategory Parent category instance.
     *
     * @return Mage_Catalog_Model_Category
     */
    public function addCategoryTree($categoryData, Mage_Catalog_Model_Category $parentCategory)
    {
        $name = trim($categoryData['name']);

        /* Check if category is already exists (by name and Parent ID). */
        /** @var Mage_Catalog_Model_Resource_Category_Collection $categoriesCollection */
        $categoriesCollection = Mage::getSingleton('catalog/category')->getCollection();
        $categoriesCollection->addFieldToFilter('name', $name)
            ->addFieldToFilter('parent_id', $parentCategory->getId());

        if ($categoriesCollection->count()) {
            /** @var Mage_Catalog_Model_Category $category */
            $category = $categoriesCollection->getFirstItem();
        } else {
            /** @var Mage_Catalog_Model_Category $category */
            $category = Mage::getModel('catalog/category');
            $category->setStoreId(0);
            $modelData = array(
                'parent_id' => $parentCategory->getId(),
                'path' => $parentCategory->getPath(),
                'is_active' => '1',
                'include_in_menu' => '1'
            );
            foreach ($categoryData as $attribute => $value) {
                if ($attribute == 'children') {
                    continue;
                }
                $modelData[$attribute] = $value;
            }

            $category->setData($modelData);
            $category->save();
        }

        if ($category->getId() && isset($categoryData['children'])) {
            foreach ($categoryData['children'] as $childCategoryData) {
                $this->addCategoryTree($childCategoryData, $category);
            }
        }

        return $category;
    }

    /**
     * Parse category tree from following format:
     * -------------
     * Root Category
     *     Category 1
     *         Sub-category 1.1
     *             Sub-sub-category 1.1.1
     *             ...
     *         Sub-category 1.2
     *     Category 2
     *     ...
     * -------------
     * Indentation: Tabs.
     *
     * @param string $sourcePath Path to source file to parse.
     *
     * @return array
     */
    public function parseCategoryTree($sourcePath)
    {
        $parents = array();
        if (is_readable($sourcePath)) {
            $handle = fopen($sourcePath, 'r');
            while (!feof($handle)) {
                $line = fgets($handle);

                $level = $this->getIndentationLevel($line);
                $node = array(
                    'name' => trim($line),
                );
                $parents[$level] = &$node;
                if ($level > 0) {
                    $parents[$level - 1]['children'][] = &$node;
                }
                unset($node);
            }
            fclose($handle);
        }

        return $parents[0]; // which contains link to root node.
    }

    /**
     * Get indentation level of a tree node in a line.
     *
     * @param string $line        Line with indented label.
     * @param string $indentation Indentation part.
     *
     * @return int
     */
    protected function getIndentationLevel($line, $indentation = "\t")
    {
        $level = 0;
        while (substr($line, $level, 1) == $indentation) {
            $level++;
        }
        return $level;
    }

    /**
     * Create a new attribute set based on "Default" set.
     *
     * @param string $name       Attribute set name.
     * @param string $entityType Entity type.
     *
     * @return int Attribute set new created ID
     */
    public function addAttributeSetBasedOnDefault($name, $entityType)
    {
        $entityTypeId = $this->getEntityType($entityType, 'entity_type_id');

        /** @var $attrSet Mage_Eav_Model_Entity_Attribute_Set */
        $attrSet = Mage::getModel('eav/entity_attribute_set')
            ->setEntityTypeId($entityTypeId)
            ->setAttributeSetName($name)
            ->save();
        $attrSet->initFromSkeleton($entityTypeId)->save();
        return $attrSet->getId();
    }

    /**
     * Remove attribute from Default set.
     *
     * @param string $entityType Type of Entity.
     * @param string $attrCode   Attribute code.
     *
     * @return void
     */
    public function removeAttributeFromDefaultSet($entityType, $attrCode)
    {
        $attributeId = $this->getAttribute($entityType, $attrCode, 'attribute_id');
        if ($attributeId) {
            $entityTypeId = $this->getEntityType($entityType, 'entity_type_id');

            /** @var Mage_Eav_Model_Resource_Entity_Attribute_Set_Collection $sets */
            $sets = Mage::getModel('eav/entity_attribute_set')->getCollection();
            $sets->setEntityTypeFilter($entityTypeId);
            $sets->addFieldToFilter('attribute_set_name', 'Default');
            $defaultSet = $sets->getFirstItem();

            $table = $this->getTable('eav/entity_attribute');
            $where = array(
                'entity_type_id = ?' => $entityTypeId,
                'attribute_set_id = ?' => $defaultSet->getId(),
                'attribute_id = ?' => $attributeId,
            );

            $this->_conn->delete($table, $where);
        }
    }
}