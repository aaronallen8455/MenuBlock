<?php

namespace AAllen\MenuBlock\Setup;


use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /**
         * Create the table 'aallen_menublock_block'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('aallen_menublock_block')
        )->addColumn(
            'block_id', Table::TYPE_SMALLINT,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Block ID'
        )->addColumn(
            'name',
            Table::TYPE_TEXT,
            150,
            ['nullable'=>false, 'default'=>''],
            'Block Name'
        )->addColumn(
            'css',
            Table::TYPE_TEXT,
            50,
            ['nullable'=>true, 'default'=>'aallen-menu-block'],
            'CSS class'
        )->addColumn(
            'position',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'default'=>'1'],
            'Position'
        )->addColumn(
            'is_active',
            Table::TYPE_SMALLINT,
            null,
            ['nullable'=>false, 'default'=>'1'],
            'Is Block Active?'
        )->addColumn(
            'creation_time',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable'=>false, 'default'=>Table::TIMESTAMP_INIT],
            'Creation Time'
        )->addColumn(
            'update_time',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable'=>false, 'default'=>Table::TIMESTAMP_INIT_UPDATE],
            'Update Time'
        )->addColumn(
            'child_block_id',
            Table::TYPE_SMALLINT,
            null,
            ['nullable' => true],
            'Child Block ID'
        )->addColumn(
            'width',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => true],
            'Child block Width'
        )->addColumn(
            'url',
            Table::TYPE_TEXT,
            300,
            ['nullable'=>true],
            'Link URL'
        )->addColumn(
            'target',
            Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default'=>'1'],
            'Link Target'
        )->addForeignKey(
            $setup->getFkName('aallen_menublock_block', 'child_block_id', 'cms_block', 'block_id'),
            'child_block_id',
            $setup->getTable('cms_block'),
            'block_id',
            Table::ACTION_CASCADE
        )->setComment('AAllen MenuBlock Blocks');

        $installer->getConnection()->createTable($table);

        /**
         * Create table 'aallen_menublock_block_store'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('aallen_menublock_block_store')
        )->addColumn(
            'block_id',
            Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'primary' => true],
            'Block ID'
        )->addColumn(
            'store_id',
            Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'primary' => true],
            'Store ID'
        )->addIndex(
            $installer->getIdxName('aallen_menublock_block_store', ['store_id']),
            ['store_id']
        )->addForeignKey(
            $installer->getFkName('aallen_menublock_block_store', 'block_id', 'aallen_menublock_block', 'block_id'),
            'block_id',
            $installer->getTable('aallen_menublock_block'),
            'block_id',
            Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName('aallen_menublock_block_store', 'store_id', 'store', 'store_id'),
            'store_id',
            $installer->getTable('store'),
            'store_id',
            Table::ACTION_CASCADE
        )->setComment(
            'AAllen MenuBlock To Store Linkage Table'
        );
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}