<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class LogService
{
    public function info(string $message, string $path)
    {
        Log::channel($this->getLogChannel($path))->info($message);
    }

    public function error(string $message, string $path)
    {
        Log::channel($this->getLogChannel($path))->error($message);
    }

    public function warn(string $message, string $path)
    {
        Log::channel($this->getLogChannel($path))->warning($message);
    }

    protected function getLogChannel(string $path): string
    {
        $filename = basename($path, '.log');
        $channel = 'custom_' . $filename;

        if (! config()->has("logging.channels.{$channel}")) {

            $logPath = storage_path('logs/' . basename($path));

            config([
                "logging.channels.{$channel}" => [
                    'driver' => 'single',
                    'path' => $logPath,
                    'level' => 'debug',
                ],
            ]);
        }

        return 'custom_' . $filename;
    }
}
