<?php

namespace IRWeb\LaravelJMS\Console\Commands;

use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\XcacheCache;
use Doctrine\Common\Persistence\ManagerRegistry;
use InvalidArgumentException;
use LogicException;

class ClearCacheCommand extends Command
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
        $cachePath = app('jms.cache');

        if(file_exists($cachePath)) unlink($cachePath);
    }
}
