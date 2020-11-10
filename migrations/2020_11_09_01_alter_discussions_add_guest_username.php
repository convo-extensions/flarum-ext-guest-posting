<?php

use Flarum\Database\Migration;

return Migration::addColumns('discussions', [
    'guest_username' => ['string', 'length' => 150, 'nullable' => true],
]);
