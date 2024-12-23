<?php

namespace App\Listeners;

use App\Events\DeliveryLocationEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class LocationUpdatedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DeliveryLocationEvent $event): void
    {
        $deliveryId = $event->deliveryId;
        $latitude = $event->latitude;
        $longitude = $event->longitude;

        Redis::set('free_deliveries_location:' . $deliveryId, json_encode([
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]));

        Log::info($event->deliveryId);
    }
}
