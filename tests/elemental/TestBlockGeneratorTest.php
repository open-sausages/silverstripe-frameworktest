<?php

use SilverStripe\Dev\SapphireTest;

class TestBlockGeneratorTest extends SapphireTest
{
    public function testCreatesDifferentBlocks()
    {
        $g = (new TestBlockGenerator())->setLimit(2);
        $block1 = next($g);
        $block2 = next($g);
        $this->assertNotNull($block1);
        $this->assertNotNull($block2);
        $this->assertNotEquals($block1, $block2);
    }
}
