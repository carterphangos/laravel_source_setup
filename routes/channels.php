<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('comment', function () {
    return true;
});