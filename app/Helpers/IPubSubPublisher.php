<?php

namespace App\Helpers;

interface IPubSubPublisher
{
    public function publish(string $topic, array $data): void;

    public function subscribe(array $topics, array $functions);
}