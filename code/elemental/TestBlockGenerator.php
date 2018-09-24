<?php

use DNADesign\Elemental\Models\ElementContent;
use SilverStripe\ElementalFileBlock\Block\FileBlock;
use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\ElementalBannerBlock\Block\Bla;

/**
 * Creates one or more blocks, based on available block types.
 * Has special behaviour for some blocks (e.g. assigning files to a file block).
 * Couples directly to different block implementations, which is fine because
 * it's in a "frameworktest" module.
 */
class TestBlockGenerator implements \IteratorAggregate
{
    /**
     * @var callable[]
     */
    protected $creatorFns = [];

    /**
     * Tri-state: True to publish, false to leave as draft, null to randomise
     *
     * @var boolean
     */
    protected $publish = null;

    /**
     * Limit of items to retrieve (important to avoid infinite loops)
     *
     * @var integer
     */
    protected $limit = 50;

    public function __construct($limit = null)
    {
        if ($limit) {
            $this->limit = $limit;
        }

        $this->creatorFns[] = function () {
            $block = new ElementContent();
            $block->write();
            return $block;
        };

        if (class_exists(FileBlock::class)) {
            $this->creatorFns[] = function () {
                $block = new FileBlock();
                $block->write();
                $image = Image::get()->first();
                if ($image) {
                    $block->Image = $image;
                }
                return $block;
            };
        }

        if (class_exists(Bla::class)) {
            $this->creatorFns[] = function () {
                $block = new FileBlock();
                $block->write();
                $image = Image::get()->first();
                if ($image) {
                    $block->Image = $image;
                }
                return $block;
            };
        }
    }

    /**
     * @return \Iterator
     */
    public function getIterator()
    {
        yield $this->getRandomBlock();
    }

    /**
     * @return BaseElement
     */
    public function getRandomBlock()
    {
        $block = $this->creatorFns[rand(0, count($this->creatorFns)-1)];

        if ($this->publish === true) {
            $page->publish('Stage', 'Live');
        } elseif ($this->publish === null && rand(0, 1) === 1) {
            $page->publish('Stage', 'Live');
        } else {
            // no-op, keep draft
        }

        return $block;
    }

    /**
     * @return  boolean
     */
    public function getPublish()
    {
        return $this->publish;
    }

    /**
     * @param  boolean  $publish
     *
     * @return  self
     */
    public function setPublish($publish)
    {
        $this->publish = $publish;

        return $this;
    }

    /**
     * @return  integer
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param  integer  $limit
     *
     * @return  self
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }
}
