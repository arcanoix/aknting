<?php

namespace Modules\Pos\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as Provider;
use Modules\Pos\Listeners\CloneBarcode;

class Event extends Provider
{
    protected $listen = [
        'cloner::cloned: App\Models\Common\Item' => [
            CloneBarcode::class,
        ],
    ];

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return true;
    }

    /**
     * Get the listener directories that should be used to discover events.
     *
     * @return array
     */
    protected function discoverEventsWithin()
    {
        return [
            __DIR__ . '/../Listeners',
        ];
    }
}
