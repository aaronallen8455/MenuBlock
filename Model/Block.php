<?php

namespace AAllen\MenuBlock\Model;

use AAllen\MenuBlock\Api\Data\BlockInterface;
use Magento\Framework\Model\AbstractModel;

class Block extends AbstractModel implements BlockInterface
{
    /**#@+
     * Block's Statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    /**#@-*/

    /**#@+
     * Link's Targets
     */
    const TARGET_TOP = 1;
    const TARGET_BLANK = 2;
    /**#@-*/

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'aallen_block';

    /**
     * Block factory
     * 
     * @var \Magento\Cms\Model\BlockFactory $_blockFactory
     */
    protected $_blockFactory;

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('AAllen\MenuBlock\Model\ResourceModel\Block');
    }

    /**
     * Prepare Block's statuses.
     * Available event aallen_Block_get_available_statuses to customize statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }

    /**
     * Get array of targets
     *
     * @return array
     */
    public function getAvailableTargets()
    {
        return [self::TARGET_BLANK => '_blank', self::TARGET_TOP => '_top'];
    }

    /**
     * Get child block's ID
     * 
     * @return int
     */
    public function getChildBlockId()
    {
        return $this->getData(self::CHILD_BLOCK_ID);
    }

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->getData(self::BLOCK_ID);
    }

    /**
     * Get name
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * Get CSS class name
     * 
     * @return string|null
     */
    public function getCss()
    {
        return $this->getData(self::CSS);
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->getData(self::URL);
    }

    /**
     * Get target
     * 
     * @return int
     */
    public function getTarget()
    {
        return $this->getData(self::TARGET);
    }

    /**
     * Get width
     * 
     * @return int
     */
    public function getWidth()
    {
        return $this->getData(self::WIDTH);
    }

    /**
     * Get creation time
     *
     * @return string|null
     */
    public function getCreationTime()
    {
        return $this->getData(self::CREATION_TIME);
    }

    /**
     * Get update time
     *
     * @return string|null
     */
    public function getUpdateTime()
    {
        return $this->getData(self::UPDATE_TIME);
    }

    /**
     * Get position
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->getData(self::POSITION);
    }

    /**
     * Receive block store ids
     *
     * @return int[]
     */
    public function getStores()
    {
        return $this->hasData('stores') ? $this->getData('stores') : $this->getData('store_id');
    }

    /**
     * Is active
     *
     * @return bool|null
     */
    public function isActive()
    {
        return (bool) $this->getData(self::IS_ACTIVE);
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return BlockInterface
     */
    public function setId($id)
    {
        return $this->setData(self::BLOCK_ID, $id);
    }

    /**
     * Set child block
     * 
     * @param int $id
     * @return $this
     */
    public function setChildBlock($id)
    {
        return $this->setData(self::CHILD_BLOCK_ID, $id);
    }

    /**
     * Set target
     * 
     * @param string $target
     * @return $this
     */
    public function setTarget($target)
    {
        return $this->setData(self::TARGET, $target);
    }

    /**
     * Set url
     * 
     * @param $url
     * @return $this
     */
    public function setUrl($url)
    {
        return $this->setData(self::URL, $url);
    }

    /**
     * Set width
     * 
     * @param $width
     * @return $this
     */
    public function setWidth($width)
    {
        return $this->setData(self::WIDTH, $width);
    }

    /**
     * Set name
     *
     * @param string $name
     * @return BlockInterface
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return BlockInterface
     */
    public function setCreationTime($creationTime)
    {
        return $this->setData(self::CREATION_TIME, $creationTime);
    }

    /**
     * Set update time
     *
     * @param string $updateTime
     * @return BlockInterface
     */
    public function setUpdateTime($updateTime)
    {
        return $this->setData(self::UPDATE_TIME, $updateTime);
    }

    /**
     * @param int $position
     * @return BlockInterface
     */
    public function setPosition($position)
    {
        return $this->setData(self::POSITION, $position);
    }

    /**
     * Set is active
     * 
     * @param int|bool $isActive
     * @return BlockInterface
     */
    public function setIsActive($isActive)
    {
        return @$this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * @param $cssClass
     * @return BlockInterface
     */
    public function setCss($cssClass)
    {
        return $this->setData(self::CSS, $cssClass);
    }
}