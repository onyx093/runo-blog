<?php

namespace App\Interfaces;

interface INotificationChannelProvider
{
    public static function getChannels(): array;
}
