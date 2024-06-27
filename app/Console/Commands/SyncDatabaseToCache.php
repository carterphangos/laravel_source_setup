<?php

namespace App\Console\Commands;

use App\Models\Announcement;
use App\Models\BulletinBoard;
use App\Models\Category;
use App\Models\Media;
use App\Services\CacheService;
use Illuminate\Console\Command;

class SyncDatabaseToCache extends Command
{
    protected $signature = 'app:sync-database-to-cache';

    protected $description = 'Sync database cache every 5 minutes';

    protected $cacheService;

    public function __construct(CacheService $cacheService)
    {
        parent::__construct();
        $this->cacheService = $cacheService;
    }

    public function handle()
    {

        $this->cacheService->syncModelData(Announcement::class, ['author', 'categories']);
        $this->cacheService->syncModelData(Category::class, []);

        $this->info('Database cache synchronized successfully.');
    }
}
