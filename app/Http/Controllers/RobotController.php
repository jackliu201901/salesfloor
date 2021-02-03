<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ReadFileController;
use Illuminate\Http\Request;

/**
 * @class        RobotController
 * @brief        robot move block Logic
 *
 * @author
 * @copyright (C) 2021 Sales Floor
 *
 * @version       1.0
 * @date          2021-02-02
 */
class RobotController extends Controller
{
    var $Blocks;
    var $fileName;

    public function __construct($fileName = "input.txt")
    {
        $this->fileName = $fileName;
    }

    /**
     * @name excuteCommand
     * @brief excute Command
     *
     * @return void
     *
     * @author        Liu
     * @date          2021-02-02
     */
    public function excuteCommand():void
    {
        $objRead = new ReadFileController();

        $arrBlocks = $objRead->ReadFile($this->fileName);

        for ($i = 0; $i < count($arrBlocks[0][0]); $i++) {
            $this->Blocks[$i][] = $i;
        }

        foreach($arrBlocks as $key => $block){

            if($block[0] == "quit"){
                break;
            }
            if(is_numeric($block[0])){
                continue;
            }

            $nubmerA = $block[1];
            $nubmerB = $block[3];

            $positionA = $this->findPosition($nubmerA);
            $positionB = $this->findPosition($nubmerB);

            if ($positionA !== $positionB){

                if($block[0] == "move"){

                    if($block[2] == "onto"){
                        $this->moveOnto($nubmerA, $nubmerB);
                    }

                    if($block[2] == "over"){
                        $positionB = $this->findPosition($nubmerB);
                        $this->moveOver($nubmerA, $positionB);
                    }
                }
                if($block[0] == "pile"){

                    if($block[2] == "onto"){
                        $this->pileOnto($nubmerA, $nubmerB);
                    }

                    if($block[2] == "over"){
                        $positionB = $this->findPosition($nubmerB);
                        $this->pileOver($nubmerA, $positionB);
                    }
                }
            }
        }

        foreach($this->Blocks as $key => $block){
                print_r($key . ":" . " " .implode(" ",$block). "\n");
         }

    }

    /**
     * @name moveOnto
     * @brief move one block on top of another block
     * @param int $nubmerA
     *        int $nubmerB
     *
     * @return void
     * @author  Liu
     * @date  2021-02-02
     */
    private function moveOnto(int $nubmerA, int $nubmerB):void
    {

        $positionB = $this->findPosition($nubmerB);

        $this->clearAbove($nubmerA);

        $key = $this->findKey($nubmerB, $positionB);

        $count = count($this->Blocks[$positionB]);

        $newKey = $key + 1;

        $this->Blocks[$positionB] = array_merge(
                array_slice($this->Blocks[$positionB], 0,$key + 1, true),
                 array( "$newKey" => "$nubmerA"),
                 array_slice($this->Blocks[$positionB], $key + 1, $count-($key + 1) , true)
        );
    }

    /**
     * @name moveOver
     * @brief move one block above  another block
     * @param int $nubmerA
     *        int $positionB
     *
     * @return void
     * @author  Liu
     * @date  2021-02-02
     */
    public function moveOver(int $nubmerA, int $positionB):void
    {
        $this->clearAbove($nubmerA);

        array_push($this->Blocks[$positionB], $nubmerA);

    }

    /**
     * @name moveOnto
     * @brief move one pile on top of  another pile
     * @param int $nubmerA
     *        int $nubmerB
     *
     * @return void
     * @author  Liu
     * @date  2021-02-02
     */
    private function pileOnto(int $nubmerA, int $nubmerB):void
    {

        $positionA = $this->findPosition($nubmerA);
        $keyA = $this->findKey($nubmerA, $positionA);
        $BlocksA = array_splice($this->Blocks[$positionA], $keyA , count($this->Blocks[$positionA]) - $keyA);

        $positionB = $this->findPosition($nubmerB);
        $keyB = $this->findKey($nubmerB, $positionB);
        $countB = count($this->Blocks[$positionB]);

        $this->Blocks[$positionB] = array_merge(
            array_slice($this->Blocks[$positionB], 0,$keyB + 1, true),
            $BlocksA,
            array_slice($this->Blocks[$positionB], $keyB + 1, $countB-($keyB + 1) , true)
        );
    }

    /**
     * @name pileOver
     * @brief move one pile above  another pile
     * @param int $nubmerA
     *        int $positionB
     *
     * @return void
     * @author  Liu
     * @date  2021-02-02
     */
    private function pileOver(int $nubmerA, int $positionB):void
    {
        $positionA = $this->findPosition($nubmerA);
        $keyA = $this->findKey($nubmerA, $positionA);

        $BlocksA = array_splice($this->Blocks[$positionA], $keyA , count($this->Blocks[$positionA]) - $keyA);

        $this->Blocks[$positionB] = array_merge($this->Blocks[$positionB], $BlocksA);
    }

    /**
     * @name clearAbove
     * @brief  clear any block on top of a back to their original position
     * @param int $nubmer
     *
     * @return void
     * @author  Liu
     * @date  2021-02-02
     */
    private function clearAbove(int $nubmer):void
    {
        $position = $this->findPosition($nubmer);

        $key = $this->findKey($nubmer, $position);

        array_splice($this->Blocks[$position], $key, 1);
    }

    /**
     * @name findPosition
     * @brief  find the position of number
     * @param int $nubmer
     *
     * @return void
     * @author  Liu
     * @date  2021-02-02
     */
    private function findPosition(int $nubmer):int
    {
        foreach($this->Blocks as $key => $value){
            if (in_array($nubmer, $value)) {
                return $key;
            }
        }
        return -1;
    }

    /**
     * @name findKey
     * @brief  find the key in the position of number
     * @param int $nubmer
     *        int $position
     *
     * @return void
     * @author  Liu
     * @date  2021-02-02
     */
    private function findKey(int $nubmer, int $position):int
    {
        $key = array_search($nubmer , $this->Blocks[$position]);

        return $key;
    }

}
