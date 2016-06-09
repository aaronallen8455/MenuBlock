<?php

namespace AAllen\MenuBlock\Model\ResourceModel\Block;


use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Identifier field name for collection items
     *
     * Can be used by collections with items without defined
     *
     * @var string
     */
    protected $_idFieldName = 'block_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('AAllen\MenuBlock\Model\Block', 'AAllen\MenuBlock\Model\ResourceModel\Block');
    }
}