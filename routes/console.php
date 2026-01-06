<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('sanitize:db')->daily();
