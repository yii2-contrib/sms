<?php

namespace YiiContribTest\Skeleton;

use PHPUnit\Framework\TestCase;
use YiiContrib\Skeleton\Skeleton;

class SkeletonTest extends TestCase
{
    public function testInstance()
    {
        $skeleton = new Skeleton();
        
        $this->assertTrue($skeleton->run());
    }
}
