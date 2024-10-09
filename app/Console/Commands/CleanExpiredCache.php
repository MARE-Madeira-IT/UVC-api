<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanExpiredCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:clean-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleans expired cache';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::table('cache')->where('expiration', '<', strtotime(now()))->delete();
        logger("Cleaned cache");
        return 0;
    }
}
