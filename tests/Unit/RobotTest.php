<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\RobotController;

class RobotsTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $arrBlocks = [
            0 => array(0),
            1 => array(1),
            2 => array(2)
        ];

        $objRobot = new RobotController;

        for ($i = 0; $i < 3; $i++) {
            $objRobot->Blocks[$i][] = $i;
        }

        $objRobot -> moveOver(2,1 );

        $expertBlocks = [
            0 => array(0),
            1 => array(1,2),
            2 => array()
        ];

        $this->assertEquals($expertBlocks, $objRobot ->Blocks);

        $objRobot -> moveOver(1,2 );

        $expertBlocks = [
            0 => array(0),
            1 => array(2),
            2 => array(1)
        ];

        $this->assertEquals($expertBlocks, $objRobot ->Blocks);

    }
}
