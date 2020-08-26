<?php

namespace Tests\Unit;

use App\Math;
use PHPUnit\Framework\TestCase;

class MathTest extends TestCase
{

    public function testSum()
    {
        $actual = Math::sum(1, 2);
        $this->assertEquals(3, $actual);
    }
	
	public function testPow()
    {
        $actual = Math::pow(2, 8);
        $this->assertEquals(256, $actual);
    }

}
