<?php

use Flarum\Database\Migration;

return Migration::addColumns('posts', [
    'guest_username' => ['string', 'length' => 150, 'nullable' => true],
]);
