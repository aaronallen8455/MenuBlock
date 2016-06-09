<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 5/23/2016
 * Time: 12:46 AM
 */

namespace AAllen\MenuBlock\Block\Adminhtml\Block\Edit;


use AAllen\MenuBlock\Model\Block;
use AAllen\MenuBlock\Model\Block\Source\ChildBlockId;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Store\Model\System\Store;

class Form extends Generic
{
    /**
     * @var Store
     */
    protected $_systemStore;

    /** @var  ChildBlockId */
    protected $_childBlockId; //get options array

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Store $systemStore
     * @param array $data
     * @param ChildBlockId $childBlockId
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Store $systemStore,
        array $data,
        ChildBlockId $childBlockId
    )
    {
        $this->_childBlockId = $childBlockId;
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('block_form');
        $this->setTitle(__('Block Information'));
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var Block $model */
        $model = $this->_coreRegistry->registry('menublock_block');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $form->setHtmlIdPrefix('block_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );

        if ($model->getBlockId()) {
            $fieldset->addField('block_id', 'hidden', ['name' => 'block_id']);
        }

        $fieldset->addField(
            'name',
            'text',
            ['name' => 'name', 'label' => __('Block Text'), 'title' => __('Block Text'), 'required' => true]
        );

        $fieldset->addField(
            'child_block_id',
            'select',
            [
                'label' => __('CMS Block'),
                'title' => __('CMS Block'),
                'name' => 'child_block_id',
                'required' => false,
                'options' => (['' => 'No CMS Block'] + $this->_childBlockId->toOptionArray(true))
            ]
        );
        
        $fieldset->addField(
            'width',
            'text',
            [
                'name' => 'width', 
                'label' => __('Width'), 
                'title' => __('Width'), 
                'required' => false, 
                'note' => 'Sets the width in pixels of the CMS block if specified.']
        );

        $fieldset->addField(
            'position',
            'text',
            [
                'label' => __('Position'),
                'title' => __('Position'),
                'name' => 'position',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'css',
            'text',
            [
                'label' => __('CSS Class'),
                'title' => __('CSS Class'),
                'name' => 'css',
                'required' => false,
                'note' => 'Additional CSS classes.'
            ]
        );

        $fieldset->addField(
            'url',
            'text',
            [
                'name' => 'url',
                'label' => __('URL'),
                'title' => __('URL'),
                'required' => false,
                'note' => 'Can be an absolute or relative path.'
            ]
        );

        $fieldset->addField(
            'target',
            'select',
            [
                'label' => __('Target'),
                'title' => __('Target'),
                'name' => 'target',
                'required' => true,
                'options' => ['1' => '_top', '2' => '_blank']
            ]
        );

        $fieldset->addField(
            'is_active',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'is_active',
                'required' => true,
                'options' => ['1' => __('Enabled'), '0' => __('Disabled')]
            ]
        );
        if (!$model->getId()) {
            $model->setData('is_active', '1');
        }

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}