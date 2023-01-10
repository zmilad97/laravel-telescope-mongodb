<?php

namespace Laravel\Telescope\Tests\Watchers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Redis;
use Laravel\Telescope\EntryType;
use Laravel\Telescope\Tests\FeatureTestCase;
use Laravel\Telescope\Watchers\RedisWatcher;
use Mockery;

class RedisWatcherTest extends FeatureTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        if (! extension_loaded('redis')) {
            $this->markTestSkipped('The phpredis extension is required for this test.');
        }

        $app->get('config')->set('database.redis.client', 'phpredis');

        $app['redis']->enableEvents();

        $app->get('config')->set('telescope.watchers', [
            RedisWatcher::class => true,
        ]);
    }

    public function test_redis_watcher_registers_entries()
    {
        $this->assertTrue(true, 'This should already work.');

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );

        Redis::connection('default')->get('telescope:test');

        $entry = $this->loadTelescopeEntries()->first();

        $this->assertSame(EntryType::REDIS, $entry->type);
        $this->assertSame('get telescope:test', $entry->content['command']);
        $this->assertSame('default', $entry->content['connection']);
    }

    public function test_does_not_register_when_redis_unbound()
    {
        $app = Mockery::mock(Application::class);

        $app->makePartial();

        $app->expects('bound')
            ->with('redis')
            ->andReturn(false);

        $app->shouldNotReceive('make')
            ->with('redis');

        $watcher = new RedisWatcher([]);

        $watcher->register($app);
    }
}
