<?php

namespace Laravel\Telescope\Tests\Watchers;

use Dummies\DummyEvent;
use Dummies\DummyEventListener;
use Dummies\DummyEventSubscriber;
use Dummies\DummyInvokableEventListener;
use Dummies\IgnoredEvent;
use Illuminate\Support\Facades\Event;
use Laravel\Telescope\EntryType;
use Laravel\Telescope\Tests\FeatureTestCase;
use Laravel\Telescope\Watchers\EventWatcher;

class EventWatcherTest extends FeatureTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app->get('config')->set('telescope.watchers', [
            EventWatcher::class => [
                'enabled' => true,
                'ignore' => [
                    IgnoredEvent::class,
                ],
            ],
        ]);
    }

    public function test_event_watcher_registers_any_events()
    {
        $this->assertTrue(true, 'This should already work.');

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );

        Event::listen(DummyEvent::class, function ($payload) {
            //
        });

        event(new DummyEvent);

        $entry = $this->loadTelescopeEntries()->first();

        $this->assertSame(EntryType::EVENT, $entry->type);
        $this->assertSame(DummyEvent::class, $entry->content['name']);
    }

    public function test_event_watcher_stores_payloads()
    {
        $this->assertTrue(true, 'This should already work.');

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );

        Event::listen(DummyEvent::class, function ($payload) {
            //
        });

        event(new DummyEvent('Telescope', 'Laravel', 'PHP'));

        $entry = $this->loadTelescopeEntries()->first();

        $this->assertSame(EntryType::EVENT, $entry->type);
        $this->assertSame(DummyEvent::class, $entry->content['name']);
        $this->assertArrayHasKey('data', $entry->content['payload']);
        $this->assertContains('Telescope', $entry->content['payload']['data']);
        $this->assertContains('Laravel', $entry->content['payload']['data']);
        $this->assertContains('PHP', $entry->content['payload']['data']);
    }

    public function test_event_watcher_registers_events_and_stores_payloads_with_subscriber_methods()
    {
        $this->assertTrue(true, 'This should already work.');

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );

        Event::listen(DummyEvent::class, DummyEventSubscriber::class.'@handleDummyEvent');

        event(new DummyEvent('Telescope', 'Laravel', 'PHP'));

        $entry = $this->loadTelescopeEntries()->first();

        $this->assertSame(EntryType::EVENT, $entry->type);
        $this->assertSame(DummyEvent::class, $entry->content['name']);
        $this->assertArrayHasKey('data', $entry->content['payload']);
        $this->assertContains('Telescope', $entry->content['payload']['data']);
        $this->assertContains('Laravel', $entry->content['payload']['data']);
        $this->assertContains('PHP', $entry->content['payload']['data']);
    }

    public function test_event_watcher_registers_events_and_stores_payloads_with_subscriber_classes()
    {
        $this->assertTrue(true, 'This should already work.');

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );;

        Event::listen(DummyEvent::class, [DummyEventSubscriber::class, 'handleDummyEvent']);

        event(new DummyEvent('Telescope', 'Laravel', 'PHP'));

        $entry = $this->loadTelescopeEntries()->first();

        $this->assertSame(EntryType::EVENT, $entry->type);
        $this->assertSame(DummyEvent::class, $entry->content['name']);
        $this->assertArrayHasKey('data', $entry->content['payload']);
        $this->assertContains('Telescope', $entry->content['payload']['data']);
        $this->assertContains('Laravel', $entry->content['payload']['data']);
        $this->assertContains('PHP', $entry->content['payload']['data']);
    }

    public function test_event_watcher_ignore_event()
    {
        $this->assertTrue(true, 'This should already work.');

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );;

        event(new IgnoredEvent());

        $entry = $this->loadTelescopeEntries()->first();

        $this->assertNull($entry);
    }

    /**
     * @dataProvider formatListenersProvider
     */
    public function test_format_listeners($listener, $formatted)
    {
        Event::listen(DummyEvent::class, $listener);

        $method = new \ReflectionMethod(EventWatcher::class, 'formatListeners');
        $method->setAccessible(true);

        $this->assertSame($formatted, $method->invoke(new EventWatcher, DummyEvent::class)[0]['name']);
    }

    public function formatListenersProvider()
    {
        return [
            'class string' => [
                DummyEventListener::class,
                DummyEventListener::class.'@handle',
            ],
            'class string with method' => [
                DummyEventListener::class.'@handle',
                DummyEventListener::class.'@handle',
            ],
            'array class string and method' => [
                [DummyEventListener::class, 'handle'],
                DummyEventListener::class.'@handle',
            ],
            'array object and method' => [
                [new DummyEventListener, 'handle'],
                DummyEventListener::class.'@handle',
            ],
            'callable object' => [
                new DummyInvokableEventListener,
                DummyInvokableEventListener::class.'@__invoke',
            ],
            'anonymous callable object' => [
                $class = new class
                {
                    public function __invoke()
                    {
                        //
                    }
                },
                get_class($class).'@__invoke',
            ],
            'closure' => [
                function () {
                    //
                },
                sprintf('Closure at %s[%s:%s]', __FILE__, __LINE__ - 3, __LINE__ - 1),
            ],
        ];
    }
}

namespace Dummies;

class DummyEvent
{
    public $data;

    public function __construct(...$payload)
    {
        $this->data = $payload;
    }

    public function handle()
    {
        //
    }
}

class DummyEventSubscriber
{
    public function handleDummyEvent($event)
    {
        //
    }
}

class IgnoredEvent
{
    public function handle()
    {
        //
    }
}

class DummyEventListener
{
    public function handle($event)
    {
        //
    }
}

class DummyInvokableEventListener
{
    public function __invoke($event)
    {
        //
    }
}
