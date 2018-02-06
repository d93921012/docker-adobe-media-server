#!/usr/bin/php -q
<?php

if (count($argv) < 2) {
    fwrite(STDERR, "Wrong arguments!\n");
    return -1;
}

$pid = $argv[1]; 

$cmd = "ls -l /proc/{$pid}/fd | grep -v 'grep' | grep -e '/vod/'";

$ff = shell_exec($cmd);
echo $ff;

return 0;
