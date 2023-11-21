<?php

namespace App\Console\Commands;

use App\Helpers\IPubSubPublisher;
use App\Service\Implementation\AuthService;
use Illuminate\Console\Command;

class AppSubscribe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:subscribe';
    protected $description = 'Subscribe to a Redis channel';

    public function __construct(
        public IPubSubPublisher $pubSubPublisher,
        public AuthService $authService
    )
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $subscribeList = [
            'user_added_note',
        ];

        $subscribeCallbacks = [];
        $subscribeCallbacks['userAddedNote'] = function ($message, $publisher) {
            var_dump('inside method message->notes_count', $message->notes_count);
            // $this->authService->sendMessageToUser();
            $this->authService->updateNotesCount($message->notes_count, $message->user_id);

        };

        $this->pubSubPublisher->subscribe($subscribeList, $subscribeCallbacks);
    }

}
