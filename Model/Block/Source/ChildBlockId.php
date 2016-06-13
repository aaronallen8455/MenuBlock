<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 6/7/2016
 * Time: 2:44 AM
 */

namespace AAllen\MenuBlock\Model\Block\Source;


use Magento\Cms\Model\Block;
use Magento\Cms\Model\ResourceModel\Block\CollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;

class ChildBlockId implements OptionSourceInterface
{
    /** @var CollectionFactory $_blockCollectionFactory */
    protected $_blockCollectionFactory;
    
    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->_blockCollectionFactory = $collectionFactory;
    }

    /**
     * Get array of block ids/ titles
     *
     * @param bool $basic
     * @return array
     */
    public function toOptionArray($basic = false)
    {
        $array = [];
        //basic means return a simple array like [id => name]
        if (!$basic) $array[] = ['label' => 'No CMS Block', 'value' => null];
        /** @var \Magento\Cms\Model\ResourceModel\Block\Collection $blocks */
        $blocks = $this->_blockCollectionFactory->create();
        $blocks->setPageSize(15);
        $pages = $blocks->getLastPageNumber();
        for ($pageNum = 1; $pageNum<=$pages; $pageNum++) {
            $blocks->setCurPage($pageNum);
            /** @var Block $block */
            foreach ($blocks as $block) {
                if (!$basic)
                    $array[] = [
                        'label' => $block->getTitle(),
                        'value' => $block->getId()
                    ];
                else
                    $array[$block->getId()] = $block->getTitle();
            }
            $blocks->clear();
        }
        return $array;
    }
}