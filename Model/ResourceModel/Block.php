<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 5/15/2016
 * Time: 12:01 AM
 */

namespace AAllen\MenuBlock\Model\ResourceModel;


use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Model\AbstractModel;
use Magento\Store\Model\StoreManagerInterface;

class Block extends AbstractDb
{
    /**
     * Store model
     *
     * @var null|\Magento\Store\Model\Store
     */
    protected $_store = null;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    ///**
    // * Construct
    // *
    // * @param Context $context
    // * @param string|null $resourcePrefix
    // * @param StoreManagerInterface $storeManagerInterface
    // */
    //public function __construct(Context $context, $resourcePrefix = null, StoreManagerInterface $storeManagerInterface)
    //{
    //    $this->_storeManager = $storeManagerInterface;
    //
    //    parent::__construct($context, $resourcePrefix);
    //}

    /**
     * Construct
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param string $connectionName
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->_storeManager = $storeManager;
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        //this where the database table and ID column is defined.
        $this->_init('aallen_menublock_block', 'block_id');
    }

    ///**
    // * Retrieve select objects for load object data
    // *
    // * @param string $field
    // * @param mixed $value
    // * @param \AAllen\MenuBlock\Model\Block $object
    // * @return \Zend_Db_Select
    // */
    //protected function _getLoadSelect($field, $value, $object)
    //{
    //    //allows us to filter only active posts. We don't want to load inactive posts!
    //    $select = parent::_getLoadSelect($field, $value, $object);
//
    //    if ($object->getStoreId()) {
//
    //        $select->where('is_active = ?', 1)->limit(1);
    //    }
//
    //    return $select;
    //}

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param \Magento\Cms\Model\Page $object
     * @return \Magento\Framework\DB\Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId()) {
            $storeIds = [\Magento\Store\Model\Store::DEFAULT_STORE_ID, (int)$object->getStoreId()];
            $select->join(
                ['aallen_menublock_block_store' => $this->getTable('aallen_menublock_block_store')],
                $this->getMainTable() . '.block_id = aallen_menublock_block_store.page_id',
                []
            )->where(
                'is_active = ?',
                1
            )->where(
                'aallen_menublock_block_store.store_id IN (?)',
                $storeIds
            )->order(
                'aallen_menublock_block_store.store_id DESC'
            )->limit(
                1
            );
        }

        return $select;
    }

    /**
     * Retrieve load select with filter by identifier, store and activity
     *
     * @param string $identifier
     * @param int|array $store
     * @param int $isActive
     * @return \Magento\Framework\DB\Select
     */
    protected function _getLoadByIdentifierSelect($identifier, $store, $isActive = null)
    {
        $select = $this->getConnection()->select()->from(
            ['cp' => $this->getMainTable()]
        )->join(
            ['cps' => $this->getTable('aallen_menublock_block_store')],
            'cp.block_id = cps.block_id',
            []
        )->where(
            'cp.identifier = ?',
            $identifier
        )->where(
            'cps.store_id IN (?)',
            $store
        );

        if (!is_null($isActive)) {
            $select->where('cp.is_active = ?', $isActive);
        }

        return $select;
    }

    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());

            $object->setData('store_id', $stores);
        }

        return parent::_afterLoad($object);
    }

    /**
     * Process post data before saving
     *
     * @param AbstractModel $object
     * @return $this
     * @throws LocalizedException
     */
    protected function _beforeSave(AbstractModel $object)
    {
        //we need to make sure we're saving valid data, so a little validation is handled here
        if (!$this->_isValidUrl($object)) {
            throw new LocalizedException(
                __('The link URL contains invalid characters.')
            );
        }

        //validate the width field
        if ($width = $object->getWidth() && !filter_var($object->getWidth(), FILTER_VALIDATE_INT, ['min_range'=>5])) {
            throw new LocalizedException(
                __('The CMS block width is invalid.')
            );
        }

        return parent::_beforeSave($object);
    }

    /**
     * Assign block to store views
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $oldStores = $this->lookupStoreIds($object->getId());
        $newStores = (array)$object->getStores();
        if (empty($newStores)) {
            $newStores = (array)$object->getStoreId();
        }
        $table = $this->getTable('aallen_menublock_block_store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);

        if ($delete) {
            $where = ['block_id = ?' => (int)$object->getId(), 'store_id IN (?)' => $delete];

            $this->getConnection()->delete($table, $where);
        }

        if ($insert) {
            $data = [];

            foreach ($insert as $storeId) {
                $data[] = ['block_id' => (int)$object->getId(), 'store_id' => (int)$storeId];
            }

            $this->getConnection()->insertMultiple($table, $data);
        }

        return parent::_afterSave($object);
    }

    /**
     * Process block data before deleting
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _beforeDelete(\Magento\Framework\Model\AbstractModel $object)
    {
        $condition = ['block_id = ?' => (int)$object->getId()];

        $this->getConnection()->delete($this->getTable('aallen_menublock_block_store'), $condition);

        return parent::_beforeDelete($object);
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $blockId
     * @return array
     */
    public function lookupStoreIds($blockId)
    {
        $connection = $this->getConnection();

        $select = $connection->select()->from(
            $this->getTable('aallen_menublock_block_store'),
            'store_id'
        )->where(
            'block_id = ?',
            (int)$blockId
        );

        return $connection->fetchCol($select);
    }

    /**
     * Set store model
     *
     * @param \Magento\Store\Model\Store $store
     * @return $this
     */
    public function setStore($store)
    {
        $this->_store = $store;
        return $this;
    }

    /**
     * Retrieve store model
     *
     * @return \Magento\Store\Model\Store
     */
    public function getStore()
    {
        return $this->_storeManager->getStore($this->_store);
    }

    /**
     * Check for valid Href
     *
     * @param AbstractModel $object
     * @return bool
     */
    protected function _isValidUrl($object)
    {
        if ($url = $object->getData('url'))
            return preg_match('/^(http:\/\/|https:\/\/)?[\w\d\/_\-=+?#.&]+$/', $object->getData('url'));
        return true;
    }
}