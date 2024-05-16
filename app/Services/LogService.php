<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class LogService
{
    public function debug(string $message, array $path = [])
    {
        Log::debug($message, $path);
    }

    public function info(string $message, array $path = [])
    {
        Log::info($message, $path);
    }

    public function notice(string $message, array $path = [])
    {
        Log::notice($message, $path);
    }

    public function warning(string $message, array $path = [])
    {
        Log::warning($message, $path);
    }

    public function error(string $message, array $path = [])
    {
        Log::error($message, $path);
    }

    public function critical(string $message, array $path = [])
    {
        Log::critical($message, $path);
    }

    public function alert(string $message, array $path = [])
    {
        Log::alert($message, $path);
    }
    
    public function emergency(string $message, array $path = [])
    {
        Log::emergency($message, $path);
    }
}
