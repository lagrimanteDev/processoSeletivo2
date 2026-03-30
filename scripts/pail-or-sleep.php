<?php

declare(strict_types=1);

if (PHP_OS_FAMILY === 'Windows') {
    echo "Pail skipped on Windows (pcntl not available).".PHP_EOL;

    while (true) {
        sleep(3600);
    }
}

passthru('php artisan pail --timeout=0', $exitCode);
exit($exitCode);
