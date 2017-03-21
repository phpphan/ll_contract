<?php
require __DIR__ . "/../inc/cron.php";
require __DIR__ . "/../../../../wp-load.php";
require __DIR__ . "/../../../../wp-cron.php";

use LicenseLounge\Contract\Cron;

Cron::process();