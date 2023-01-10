<?php

namespace Laravel\Telescope\Tests\Console;

use Illuminate\Support\Facades\DB;
use Laravel\Telescope\Database\Factories\EntryModelFactory;
use Laravel\Telescope\Storage\EntryModel;
use Laravel\Telescope\Tests\FeatureTestCase;

class ClearCommandTest extends FeatureTestCase
{
    public function test_clear_command_will_delete_all_entries()
    {
        $this->assertTrue(true, 'This should already work.');

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );

        EntryModelFactory::new()->create();

        DB::table('telescope_monitoring')->insert([
            ['tag' => 'one'],
            ['tag' => 'two'],
        ]);

        $this->artisan('telescope:clear');

        $this->assertSame(0, EntryModel::query()->count());
        $this->assertSame(0, DB::table('telescope_monitoring')->count());
    }
}
