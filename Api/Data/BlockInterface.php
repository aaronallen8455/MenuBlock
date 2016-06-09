<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 5/14/2016
 * Time: 4:04 AM
 */

namespace AAllen\MenuBlock\Api\Data;

interface BlockInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    const BLOCK_ID = 'block_id';
    const NAME = 'name';
    const CSS = 'css';
    const CREATION_TIME = 'creation_time';
    const UPDATE_TIME = 'update_time';
    const IS_ACTIVE = 'is_active';
    const POSITION = 'position';
    const CHILD_BLOCK_ID = 'child_block_id';
    const WIDTH = 'width';
    const URL = 'url';
    const TARGET = 'target';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getID();

    /**
     * Get name
     *
     * @return string|null
     */
    public function getName();

    /**
     * Get CSS class
     * 
     * @return string|null
     */
    public function getCss();

    /**
     * Get creation time
     *
     * @return string|null
     */
    public function getCreationTime();

    /**
     * Get update time
     *
     * @return string|null
     */
    public function getUpdateTime();

    /**
     * Get position
     * 
     * @return int
     */
    public function getPosition();

    /**
     * @return string
     */
    public function getTarget();

    /**
     * Get block width
     * 
     * @return int
     */
    public function getWidth();

    /**
     * Get url
     * 
     * @return string
     */
    public function getUrl();

    /**
     * Is active
     *
     * @return bool|null
     */
    public function isActive();

    /**
     * Get child block id
     * 
     * @return int
     */
    public function getChildBlockId();

    /**
     * Set ID
     *
     * @param int $id
     * @return BlockInterface
     */
    public function setId($id);

    /**
     * Set name
     *
     * @param string $name
     * @return BlockInterface
     */
    public function setName($name);

    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return BlockInterface
     */
    public function setCreationTime($creationTime);
    
    /**
     * Set update time
     * 
     * @param string $updateTime
     * @return BlockInterface
     */
    public function setUpdateTime($updateTime);
    
    /**
     * Set is active
     * 
     * @param int|bool $isActive
     * @return BlockInterface
     */
    public function setIsActive($isActive);

    /**
     * Set CSS class
     * 
     * @param $cssClass
     * @return BlockInterface
     */
    public function setCss($cssClass);
    
    /**
     * Set position
     * 
     * @param int $position
     * @return BlockInterface
     */
    public function setPosition($position);
    
    /**
     * Set child block
     * 
     * @param int $id
     * @return BlockInterface
     */
    public function setChildBlock($id);

    /**
     * Set child block width
     * 
     * @param $width
     * @return BlockInterface
     */
    public function setWidth($width);

    /**
     * Set url
     * 
     * @param $url
     * @return BlockInterface
     */
    public function setUrl($url);

    /**
     * Set target
     *
     * @param string $target
     * @return BlockInterface
     */
    public function setTarget($target);
}