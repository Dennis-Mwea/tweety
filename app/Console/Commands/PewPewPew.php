<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PewPewPew extends Command
{
    protected $signature = 'pewpewpew';
    protected $description = 'Command description';

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
        $this->info('Hold up...');
        sleep(1);
        $this->info('Wait a minute...');
        sleep(2);
        $this->info('It\'s a chopper...');
        $this->comment($this->copter());

        return 0;
    }

    public function copter()
    {
        return '
================--+--=================
                 ~|~                        ,-~~-.
         ____/~~~~~~~======-=              :  /~> :
       /\'~~||~| |== == |-- ~-________________/  /
     _/_|__||_| ||_||_||     LARAVEL <3 TWEETY <
   (-+|    |    |______|     ___-----```````\__\
    `-+._____ ___________ _-~
   ~-________||__________||_____
     ~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        ';
    }
}
