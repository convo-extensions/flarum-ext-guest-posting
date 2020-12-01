<?php

namespace Alter\GuestPosting\Providers;

use Alter\GuestPosting\Commands\GuestVotePollHandler;
use Alter\GuestPosting\GuestManager;
use Flarum\Api\Event\Serializing;
use Flarum\Bus\Dispatcher as FlarumBusDispatcher;
use Flarum\Foundation\AbstractServiceProvider;
use FoF\Polls\Api\Serializers\PollSerializer;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Queue\Factory as QueueFactoryContract;

class PollAttributes extends AbstractServiceProvider
{
    public function register()
    {
        $this->app['events']->listen(Serializing::class, [$this, 'serializing']);

        // Workaround for https://github.com/flarum/core/issues/2480
        $this->app->singleton(BusDispatcher::class, function ($app) {
            return new FlarumBusDispatcher($app, function ($connection = null) use ($app) {
                return $app[QueueFactoryContract::class]->connection($connection);
            });
        });
    }

    public function serializing(Serializing $event)
    {
        if ($event->isSerializer(PollSerializer::class)) {
            $event->attributes['guestVoteOption'] = GuestManager::getPollVote($event->model->id);
            $event->attributes['canGuestVote'] = $event->actor->can('votePolls');
        }
    }

    public function boot()
    {
        $this->app->make(BusDispatcher::class)->pipeThrough([
            GuestVotePollHandler::class,
        ]);
    }
}
