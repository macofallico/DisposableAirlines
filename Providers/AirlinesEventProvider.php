<?php

namespace Modules\DisposableAirlines\Providers;

use App\Events\PirepCancelled;
use App\Events\PirepFiled;
use App\Events\PirepPrefiled;
use App\Events\PirepUpdated;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\DisposableAirlines\Listeners\PirepCancelledEventListener;
use Modules\DisposableAirlines\Listeners\PirepFiledEventListener;
use Modules\DisposableAirlines\Listeners\PirepPrefiledEventListener;
use Modules\DisposableAirlines\Listeners\PirepUpdatedEventListener;


class AirlinesEventProvider extends ServiceProvider
{
    // Listen Below Events
    protected $listen = [
        PirepCancelled::class => [PirepCancelledEventListener::class],
        PirepFiled::class     => [PirepFiledEventListener::class],
        PirepPrefiled::class  => [PirepPrefiledEventListener::class],
        PirepUpdated::class   => [PirepUpdatedEventListener::class],
    ];

    // Register Module Events
    public function boot()
    {
        parent::boot();
    }
}
