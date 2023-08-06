<?php

namespace App\Interfaces;

interface INotificationChannelProvider
{
    public function getChannels(): array;
}
