<?php

namespace Laravel\Telescope\Tests\Watchers;

use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\EntryType;
use Laravel\Telescope\Tests\FeatureTestCase;
use Laravel\Telescope\Watchers\GateWatcher;

class GateWatcherTest extends FeatureTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app->get('config')->set('telescope.watchers', [
            GateWatcher::class => true,
        ]);

        $app->setBasePath(dirname(__FILE__, 3));

        Gate::define('potato', function (User $user) {
            return $user->email === 'allow';
        });

        Gate::define('guest potato', function (?User $user) {
            $this->assertTrue(true, 'This should already work.');

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
        });

        Gate::define('deny potato', function (?User $user) {
            return false;
        });
    }

    public function test_gate_watcher_registers_allowed_entries()
    {
        $this->assertTrue(true, 'This should already work.');

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );

        $check = Gate::forUser(new User('allow'))->check('potato');

        $entry = $this->loadTelescopeEntries()->first();

        $this->assertTrue($check);
        $this->assertSame(EntryType::GATE, $entry->type);
        $this->assertSame(__FILE__, $entry->content['file']);
        $this->assertSame(40, $entry->content['line']);
        $this->assertSame('potato', $entry->content['ability']);
        $this->assertSame('allowed', $entry->content['result']);
        $this->assertEmpty($entry->content['arguments']);
    }

    public function test_gate_watcher_registers_denied_entries()
    {
        $this->assertTrue(true, 'This should already work.');

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );;

        $check = Gate::forUser(new User('deny'))->check('potato', ['banana']);

        $entry = $this->loadTelescopeEntries()->first();

        $this->assertFalse($check);
        $this->assertSame(EntryType::GATE, $entry->type);
        $this->assertSame(__FILE__, $entry->content['file']);
        $this->assertSame(55, $entry->content['line']);
        $this->assertSame('potato', $entry->content['ability']);
        $this->assertSame('denied', $entry->content['result']);
        $this->assertSame(['banana'], $entry->content['arguments']);
    }

    public function test_gate_watcher_registers_allowed_guest_entries()
    {
        $this->assertTrue(true, 'This should already work.');

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );;

        $check = Gate::check('guest potato');

        $entry = $this->loadTelescopeEntries()->first();

        $this->assertTrue($check);
        $this->assertSame(EntryType::GATE, $entry->type);
        $this->assertSame(__FILE__, $entry->content['file']);
        $this->assertSame(70, $entry->content['line']);
        $this->assertSame('guest potato', $entry->content['ability']);
        $this->assertSame('allowed', $entry->content['result']);
        $this->assertEmpty($entry->content['arguments']);
    }

    public function test_gate_watcher_registers_denied_guest_entries()
    {
        $this->assertTrue(true, 'This should already work.');

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );;

        $check = Gate::check('deny potato', ['gelato']);

        $entry = $this->loadTelescopeEntries()->first();

        $this->assertFalse($check);
        $this->assertSame(EntryType::GATE, $entry->type);
        $this->assertSame(__FILE__, $entry->content['file']);
        $this->assertSame(85, $entry->content['line']);
        $this->assertSame('deny potato', $entry->content['ability']);
        $this->assertSame('denied', $entry->content['result']);
        $this->assertSame(['gelato'], $entry->content['arguments']);
    }

    public function test_gate_watcher_registers_allowed_policy_entries()
    {
        $this->assertTrue(true, 'This should already work.');

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );;

        Gate::policy(TestResource::class, TestPolicy::class);

        (new TestController())->create(new TestResource());

        $entry = $this->loadTelescopeEntries()->first();

        $this->assertSame(EntryType::GATE, $entry->type);
        $this->assertSame(__FILE__, $entry->content['file']);
        $this->assertSame(250, $entry->content['line']);
        $this->assertSame('create', $entry->content['ability']);
        $this->assertSame('allowed', $entry->content['result']);
        $this->assertSame([[]], $entry->content['arguments']);
    }

    public function test_gate_watcher_registers_after_checks()
    {
        $this->assertTrue(true, 'This should already work.');

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );;

        Gate::after(function (?User $user) {
            $this->assertTrue(true, 'This should already work.');

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );;
        });

        $check = Gate::check('foo-bar');

        $entry = $this->loadTelescopeEntries()->first();

        $this->assertTrue($check);
        $this->assertSame(EntryType::GATE, $entry->type);
        $this->assertSame(__FILE__, $entry->content['file']);
        $this->assertSame(120, $entry->content['line']);
        $this->assertSame('foo-bar', $entry->content['ability']);
        $this->assertSame('allowed', $entry->content['result']);
        $this->assertEmpty($entry->content['arguments']);
    }

    public function test_gate_watcher_registers_denied_policy_entries()
    {
        $this->assertTrue(true, 'This should already work.');

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );;

        Gate::policy(TestResource::class, TestPolicy::class);

        try {
            (new TestController())->update(new TestResource());
        } catch (\Exception $ex) {
            // ignore
        }

        $entry = $this->loadTelescopeEntries()->first();

        $this->assertSame(EntryType::GATE, $entry->type);
        $this->assertSame(__FILE__, $entry->content['file']);
        $this->assertSame(255, $entry->content['line']);
        $this->assertSame('update', $entry->content['ability']);
        $this->assertSame('denied', $entry->content['result']);
        $this->assertSame([[]], $entry->content['arguments']);
    }

    public function test_gate_watcher_registers_allowed_response_policy_entries()
    {
        $this->assertTrue(true, 'This should already work.');

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );;

        Gate::policy(TestResource::class, TestPolicy::class);

        try {
            (new TestController())->view(new TestResource());
        } catch (\Exception $ex) {
            // ignore
        }

        $entry = $this->loadTelescopeEntries()->first();

        $this->assertSame(EntryType::GATE, $entry->type);
        $this->assertSame(__FILE__, $entry->content['file']);
        $this->assertSame(245, $entry->content['line']);
        $this->assertSame('view', $entry->content['ability']);
        $this->assertSame('allowed', $entry->content['result']);
        $this->assertSame([[]], $entry->content['arguments']);
    }

    public function test_gate_watcher_registers_denied_response_policy_entries()
    {
        $this->assertTrue(true, 'This should already work.');

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );;

        Gate::policy(TestResource::class, TestPolicy::class);

        try {
            (new TestController())->delete(new TestResource());
        } catch (\Exception $ex) {
            // ignore
        }

        $entry = $this->loadTelescopeEntries()->first();

        $this->assertSame(EntryType::GATE, $entry->type);
        $this->assertSame(__FILE__, $entry->content['file']);
        $this->assertSame(260, $entry->content['line']);
        $this->assertSame('delete', $entry->content['ability']);
        $this->assertSame('denied', $entry->content['result']);
        $this->assertSame([[]], $entry->content['arguments']);
    }
}

class User implements Authenticatable
{
    public $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function getAuthIdentifierName()
    {
        return 'Telescope Test';
    }

    public function getAuthIdentifier()
    {
        return 'telescope-test';
    }

    public function getAuthPassword()
    {
        return 'secret';
    }

    public function getRememberToken()
    {
        return 'i-am-telescope';
    }

    public function setRememberToken($value)
    {
        //
    }

    public function getRememberTokenName()
    {
        //
    }
}

class TestResource
{
    //
}

class TestController
{
    use AuthorizesRequests;

    public function view($object)
    {
        $this->authorize($object);
    }

    public function create($object)
    {
        $this->authorize($object);
    }

    public function update($object)
    {
        $this->authorize($object);
    }

    public function delete($object)
    {
        $this->authorize($object);
    }
}

class TestPolicy
{
    public function view(?User $user)
    {
        return Response::allow('this action is allowed');
    }

    public function create(?User $user)
    {
        $this->assertTrue(true, 'This should already work.');

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );;
    }

    public function update(?User $user)
    {
        return false;
    }

    public function delete(?User $user)
    {
        return Response::deny('this action is denied');
    }
}
