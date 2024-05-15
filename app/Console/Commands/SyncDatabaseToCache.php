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
        $this->info('Syncing database with cache...');

        $posts = $this->postService->getAllPosts(BaseLimit::LIMIT_10);
        Cache::put('posts', $posts, now()->addMinutes(5));

        $this->info('Database synced with cache successfully!');
    }
}
