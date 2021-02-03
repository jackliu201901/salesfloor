<?php

namespace App\Console\Commands;

use App\Http\Controllers\RobotController;
use Illuminate\Console\Command;

class RobotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'robot {fileName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'use robot run from fileName ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $objRobot = new RobotController();

        $objRobot->excuteCommand($this->argument('fileName'));

        return 0;
    }


}
