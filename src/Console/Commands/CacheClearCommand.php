<?php

namespace IRWeb\LaravelJMS\Console\Commands;

use Illuminate\Console\Command;

class CacheClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'jms:cache:clear';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Clear the entire cache of JMS.';

    /**
     * Execute the console command.
     *
     * @param ManagerRegistry $registry
     */
    public function fire()
    {
        $cachePath = config('jms.cache');
        $filesystem = app('filesystem.disk');

        if(count($filesystem->allFiles($cachePath)))
        {
            $filesystem->deleteDirectory($cachePath);
            $this->info('JMS cache cleared!');
        } 
    }
}
