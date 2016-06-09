<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 5/19/2016
 * Time: 10:08 PM
 */

namespace AAllen\MenuBlock\Controller\Adminhtml\Block;



use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(Context $context, PageFactory $resultPageFactory)
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Index action
     *
     * @return Page
     */
    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('AAllen_MenuBlock::blocks');
        $resultPage->addBreadcrumb(__('Menu Blocks'), __('Menu Blocks'));
        $resultPage->addBreadcrumb(__('Manage Menu Blocks'), __('Manage Menu Blocks'));
        $resultPage->getConfig()->getTitle()->prepend(__('Menu Blocks'));

        return $resultPage;
    }

    /**
     * Is the user allowed to view the blog post grid.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('AAllen_MenuBlock::blocks');
    }
}