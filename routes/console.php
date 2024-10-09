<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('cache:clean-expired')
    ->everyFiveMinutes();
