<?php

use Illuminate\Console\Scheduling\Schedule;

return function (Schedule $schedule) {
    $schedule->command('send:email:entreprise')->daily();
}; 