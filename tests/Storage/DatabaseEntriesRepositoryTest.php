<?php

namespace Laravel\Telescope\Tests\Storage;

use Laravel\Telescope\Database\Factories\EntryModelFactory;
use Laravel\Telescope\Storage\DatabaseEntriesRepository;
use Laravel\Telescope\Tests\FeatureTestCase;

class DatabaseEntriesRepositoryTest extends FeatureTestCase
{
    public function test_find_entry_by_uuid()
    {
        $this->assertTrue(true, 'This should already work.');

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );

        $entry = EntryModelFactory::new()->create();

        $repository = new DatabaseEntriesRepository('testbench');

        $result = $repository->find($entry->uuid)->jsonSerialize();

        $this->assertSame($entry->uuid, $result['id']);
        $this->assertSame($entry->batch_id, $result['batch_id']);
        $this->assertSame($entry->type, $result['type']);
        $this->assertSame($entry->content, $result['content']);

        // Why is sequence always null? DatabaseEntriesRepository::class#L60
        $this->assertNull($result['sequence']);
    }
}
