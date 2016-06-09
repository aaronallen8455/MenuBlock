<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 5/20/2016
 * Time: 4:45 AM
 */

namespace AAllen\MenuBlock\Model\Block\Source;


use AAllen\MenuBlock\Model\Block;
use Magento\Framework\Data\OptionSourceInterface;

class Target implements OptionSourceInterface
{
    /** @var Block */
    protected $block;

    /**
     * Constructor
     *
     * @param Block $block
     */
    public function __construct(Block $block)
    {
        $this->block = $block;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options[] = ['label' => '', 'value' => '']; //the blank topmost option
        $availableOptions = $this->block->getAvailableTargets();
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key
            ];
        }
        return $options;
    }
}