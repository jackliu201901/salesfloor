<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

/**
 * @class        ReadFileController
 * @brief        Read input file
 *
 * @author
 * @copyright (C) 2021 Sales Floor
 *
 * @version       1.0
 * @date          2021-02-02
 */
class ReadFileController extends Controller
{
    var $fileName;

    public function __construct($fileName = "input.txt")
    {
        $this->fileName = $fileName;
    }

    /**
     * @name ReadFile
     * @brief Read File
     *
     * @return array
     *
     * @author        Liu
     * @date          2021-02-02
     */
    public function ReadFile():array
    {
        try {

            $commandLine = array();

            if (Storage::disk('local')->exists($this->fileName)) {

                $contents = Storage::disk('local')->get($this->fileName);

                foreach (explode("\n", $contents) as $key => $line) {

                    $commandLine[$key] = explode(' ', $line);

                }
            }

            array_splice($commandLine, $commandLine[0][0] + 1, count($commandLine) - $commandLine[0][0] - 1);

            //dd($commandLine);
            return $commandLine;

        }
        catch (Exception $objException) {
            throw new Exception("Read command file error:"  . $objException->getMessage());
        }
    }

}
