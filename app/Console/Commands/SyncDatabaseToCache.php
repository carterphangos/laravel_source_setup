<?php

namespace App\Console\Commands;

use App\Models\Comment;
use App\Models\Post;
use App\Services\CacheService;
use Illuminate\Console\Command;

class SyncDatabaseToCache extends Command
{
    protected $signature = 'app:sync-database-to-cache';

    protected $description = 'Sync database posts with cache every 5 minutes';

    protected $cacheService;

    public function __construct(CacheService $cacheService)
    {
        parent::__construct();
        $this->cacheService = $cacheService;
    }

    public function handle()
    {
        $this->cacheService->syncCache(Post::class);
        $this->cacheService->syncCache(Comment::class);
    }
}
