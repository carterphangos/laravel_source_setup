<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('go-news', function () {
    return true;
});
