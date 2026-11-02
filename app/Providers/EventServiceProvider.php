<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen(\Illuminate\Mail\Events\MessageSent::class, function ($event) {
            $message = $event->message;
            
            // Extract the first 'to' email address
            $toAssoc = $message->getTo();
            $toEmail = $toAssoc ? array_key_first($toAssoc) : 'unknown@example.com';
            
            \App\Models\EmailLog::create([
                'to_email' => $toEmail,
                'subject' => $message->getSubject(),
                'body' => $message->getBody(),
            ]);
        });
    }
}
