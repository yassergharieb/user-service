<?php

namespace App\Helpers\Implementation;

use App\Helpers\IPubSubPublisher;
use Illuminate\Support\Facades\Redis;
use Predis\Client;

class RedisPubSubPublisher implements IPubSubPublisher
{
    public function publish(string $topic, array $data): void
    {
        $redisPrefix = env('REDIS_PREFIX');

        $publisher = new Client([
            "host" => env('REDIS_HOST'),
            "password" => env('REDIS_PASSWORD'),
            "port" => env("REDIS_PORT"),
        ]);

        $publisher->publish(
            $redisPrefix.$topic,
            json_encode($data)
        );
    }

    public function subscribe(array $topics, array $functions)
    {
        $redis = Redis::connection('subscriber');
        $publisher = new Client([
            "host" => env('REDIS_HOST'),
            "password" => env('REDIS_PASSWORD'),
            "port" => env("REDIS_PORT"),
        ]);

        $redis->subscribe($topics, function ($message) use ($publisher, $functions) {
            $message = json_decode($message);
            if ($message->type == 'user_added_note') {
                $functions['userAddedNote']($message, $publisher);
            }
        });
    }
}