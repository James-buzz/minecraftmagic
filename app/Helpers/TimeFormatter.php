<?php

namespace App\Helpers;

class TimeFormatter
{
    /**
     * Format duration between two micro times
     */
    public static function formatPeriod(float $endTime, float $startTime): string
    {
        $duration = $endTime - $startTime;

        $hours = (int) ($duration / 60 / 60);
        $minutes = (int) ($duration / 60) - $hours * 60;
        $seconds = (int) $duration - $hours * 60 * 60 - $minutes * 60;

        return sprintf(
            '%s:%s:%s',
            $hours == 0 ? '00' : sprintf('%02d', $hours),
            $minutes == 0 ? '00' : sprintf('%02d', $minutes),
            $seconds == 0 ? '00' : sprintf('%02d', $seconds)
        );
    }
}
