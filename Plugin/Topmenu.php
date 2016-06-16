<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 5/30/2016
 * Time: 9:17 PM
 */

namespace AAllen\MenuBlock\Plugin;


use AAllen\MenuBlock\Model\Block;
use AAllen\MenuBlock\Model\ResourceModel\Block\Collection;
use AAllen\MenuBlock\Model\ResourceModel\Block\CollectionFactory;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\LayoutInterface;
use Magento\Store\Model\StoreManager;
use Magento\Store\Model\StoreManagerInterface;

class Topmenu
{
    /** @var  CollectionFactory */
    protected $_blockCollectionFactory;

    /** @var LayoutInterface $_layout */
    protected $_layout;

    /** @var  StoreManager $_storeManager */
    protected $_storeManager;

    /**
     * Topmenu constructor.
     * @param CollectionFactory $collectionFactory
     * @param LayoutInterface $layoutInterface
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(CollectionFactory $collectionFactory,
                                LayoutInterface $layoutInterface,
                                StoreManagerInterface $storeManager)
    {
        $this->_blockCollectionFactory = $collectionFactory;
        $this->_layout = $layoutInterface;
        $this->_storeManager = $storeManager;
    }

    /**
     * Insert blocks into the Html stream
     *
     * @param $subject
     * @param $html
     * @return string
     */
    public function afterGetHtml($subject, $html)
    {
        /** @var Collection $blocks */
        $blocks = $this->_blockCollectionFactory->create();
        $blocks->addFilter('is_active', 1);
        $blocks->setOrder('position', Collection::SORT_ORDER_ASC);
        if ($blocks->count() > 0) {
            //break the html up
            $html = preg_split('/(?<!^)(?=<li\s+class="level0)/', $html);
            //insert each block into it's position.
            /** @var Block $block */
            foreach ($blocks as $block) {

                if ($url = $block->getUrl()) {
                    //check if link is relative or absolute
                    if (!preg_match('/^http(s)?:\/\//', $url)) {
                        $url = $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_LINK) . $url;
                    }
                }

                $content = '<li class="level0 level-top ' . ($block->getChildBlockId()?'parent ':'') . 'ui-menu-item ' . ($block->getCss()?:'') . '" role="presentation">';
                $content .= '<a href="' . ($url?:'') . '" class="level-top" target="' . $block->getAvailableTargets()[$block->getTarget()] . '" name="' . $block->getName() . '">';
                $content .= htmlentities($block->getName());
                $content .= '</a>';
                //render the block if set
                if ($cmsBlockId = $block->getChildBlockId()) {
                    $content .= '<ul class="level0 submenu ui-menu ui-widget ui-widget-content ui-corner-all" role="menu" aria-expanded="false" aria-hidden="true">';
                    $content .= '<li class="level1 nav-2-1 first parent ui-menu-item" role="presentation"' . (!empty($block->getWidth())?(' style="width:'.$block->getWidth().'px;"'):'') . '>';
                    $content .= $this->_layout->createBlock('Magento\Cms\Block\Block')->setBlockId($cmsBlockId)->toHtml();
                    $content .= '</li></ul>';
                }
                $content .= '</li>';

                array_splice($html, $block->getPosition()-1, 0, $content);
            }
            $html = implode('', $html);
        }
        return $html;
    }
}