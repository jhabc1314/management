<?php

namespace JackDou\Management;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use JackDou\Management\Events\Notify;
use JackDou\Management\Events\SupervisorName;
use JackDou\Management\Events\SupervisorStatus;
use JackDou\Management\Listeners\NoticeSend;
use JackDou\Management\Listeners\SupervisorPush;
use JackDou\Management\Listeners\SupervisorUpdate;

class ManagementEventProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        SupervisorName::class => [
            SupervisorUpdate::class,
        ],
        SupervisorStatus::class => [
            SupervisorPush::class,
        ],
        Notify::class => [
            NoticeSend::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
