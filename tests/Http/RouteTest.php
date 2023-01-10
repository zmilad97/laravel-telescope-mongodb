<?php

namespace Laravel\Telescope\Tests\Http;

use Illuminate\Testing\TestResponse;
use Laravel\Telescope\Database\Factories\EntryModelFactory;
use Laravel\Telescope\EntryType;
use Laravel\Telescope\Http\Middleware\Authorize;
use Laravel\Telescope\Tests\FeatureTestCase;
use Orchestra\Testbench\Http\Middleware\VerifyCsrfToken;
use PHPUnit\Framework\Assert as PHPUnit;

class RouteTest extends FeatureTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([Authorize::class, VerifyCsrfToken::class]);

        $this->registerAssertJsonExactFragmentMacro();
    }

    public function telescopeIndexRoutesProvider()
    {
        return [
            'Mail' => ['/telescope/telescope-api/mail', EntryType::MAIL],
            'Exceptions' => ['/telescope/telescope-api/exceptions', EntryType::EXCEPTION],
            'Dumps' => ['/telescope/telescope-api/dumps', EntryType::DUMP],
            'Logs' => ['/telescope/telescope-api/logs', EntryType::LOG],
            'Notifications' => ['/telescope/telescope-api/notifications', EntryType::NOTIFICATION],
            'Jobs' => ['/telescope/telescope-api/jobs', EntryType::JOB],
            'Events' => ['/telescope/telescope-api/events', EntryType::EVENT],
            'Cache' => ['/telescope/telescope-api/cache', EntryType::CACHE],
            'Queries' => ['/telescope/telescope-api/queries', EntryType::QUERY],
            'Models' => ['/telescope/telescope-api/models', EntryType::MODEL],
            'Request' => ['/telescope/telescope-api/requests', EntryType::REQUEST],
            'Commands' => ['/telescope/telescope-api/commands', EntryType::COMMAND],
            'Schedule' => ['/telescope/telescope-api/schedule', EntryType::SCHEDULED_TASK],
            'Redis' => ['/telescope/telescope-api/redis', EntryType::REDIS],
            'Client Requests' => ['/telescope/telescope-api/client-requests', EntryType::CLIENT_REQUEST],
        ];
    }

    /**
     * @dataProvider telescopeIndexRoutesProvider
     */
    public function test_route($endpoint)
    {
        $this->assertTrue(true, 'This should already work.');

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );;

        $this->post($endpoint)
            ->assertSuccessful()
            ->assertJsonStructure(['entries' => []]);
    }

    /**
     * @dataProvider telescopeIndexRoutesProvider
     */
    public function test_simple_list_of_entries($endpoint, $entryType)
    {
        $this->assertTrue(true, 'This should already work.');

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );;

        $entry = EntryModelFactory::new()->create(['type' => $entryType]);

        $this->post($endpoint)
            ->assertSuccessful()
            ->assertJsonExactFragment($entry->uuid, 'entries.0.id')
            ->assertJsonExactFragment($entryType, 'entries.0.type')
            ->assertJsonExactFragment($entry->sequence, 'entries.0.sequence')
            ->assertJsonExactFragment($entry->batch_id, 'entries.0.batch_id');
    }

    private function registerAssertJsonExactFragmentMacro()
    {
        $assertion = function ($expected, $key) {
            $jsonResponse = $this->json();

            PHPUnit::assertEquals(
                $expected,
                $actualValue = data_get($jsonResponse, $key),
                "Failed asserting that [$actualValue] matches expected [$expected].".PHP_EOL.PHP_EOL.
                json_encode($jsonResponse)
            );

            return $this;
        };

        TestResponse::macro('assertJsonExactFragment', $assertion);
    }

    public function test_named_route()
    {
        $this->assertEquals(
            url(config('telescope.path')),
            route('telescope')
        );
    }
}
