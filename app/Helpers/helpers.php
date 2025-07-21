<?php

function formatDuration(int|float $minutes): string
{
    return $minutes > 59 ? floor($minutes / 60).' hrs '.($minutes % 60).' mins' : $minutes.' mins';
}
