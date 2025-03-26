<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('channel-chat-messages', function () {
    return true;
});
