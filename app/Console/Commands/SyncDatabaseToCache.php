<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PostService;
use Illuminate\Support\Facades\Cache;
use App\Enums\BaseLimit;

class SyncDatabaseToCache extends Command
{
    protected $signature = 'app:sync-database-to-cache';
    protected $description = 'Sync database posts with cache every 5 minutes';

    protected $postService;

    public function __construct(PostService $postService)
    {
        parent::__construct();
        $this->postService = $postService;
    }

    public function handle()
    {
        $cacheKey = $this->generateCacheKey();

        $posts = $this->postService->getAllPosts(BaseLimit::LIMIT_10);
        Cache::put($cacheKey, $posts, now()->addMinutes(5));
    }

    private function generateCacheKey(): string
    {
        return 'postsPage1';
    }
}
