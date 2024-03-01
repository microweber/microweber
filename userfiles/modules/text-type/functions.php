<?php

function convertTextSpeedToNumber($textSpeed) {
    $speed = 110;
    if ($textSpeed == 'slow') {
        $speed = 90;
    }
    if ($textSpeed == 'medium') {
        $speed = 80;
    }
    if ($textSpeed == 'high') {
        $speed = 70;
    }
    if ($textSpeed == 'fast') {
        $speed = 60;
    }
    if ($textSpeed == 'ultra_fast') {
        $speed = 50;
    }
    return $speed;
}
