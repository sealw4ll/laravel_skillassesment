<?php

namespace App\Listeners;

use App\Events\BidCreated;
use App\Models\Bid;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\QueryException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BidSaved implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */

    public function handle(BidCreated $event)
    {
        User::chunk(20, function($users){
            foreach ($users as $user) {
                $user_last_bid = Bid::where('user_id', $user->id)->latest('created_at')->first();
                $highest_bid_price = Bid::latest('created_at')->first()->price;

                $notificationData = [
                    'latest_bid_price' => strval(number_format($highest_bid_price, 2, '.', '')),
                    'user_last_bid_price' => $user_last_bid ? strval(number_format($user_last_bid->price, 2, '.', '')) : '0.00',
                ];

                $this->createNotification($user->id, $notificationData);
                // Notification::create([
                //     'id' => $this->generateRandomIntId(),
                //     'type' => 'type',
                //     'notifiable_type' => 'notifiable_type',
                //     'notifiable_id' => $user->id,
                //     'data' => json_encode($notificationData)
                // ]);
            }
        });
    }

    function createNotification($id, $data){
        try {
            Notification::create([
                'id' => $this->generateRandomIntId(),
                'type' => 'type',
                'notifiable_type' => 'notifiable_type',
                'notifiable_id' => $id,
                'data' => json_encode($data)
            ]);
        } catch (QueryException $e) {
            // Handle the exception, for example:
            if ($e->getCode() === '23000') {
                $this->createNotification($id, $data);
            }
            else {
                echo $e->getMessage();
    exit;
            }
        }
    }

    function generateRandomIntId($min = 1, $max = 100000000) {
        return mt_rand($min, $max);
    }
}
