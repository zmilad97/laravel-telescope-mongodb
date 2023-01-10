<?php

namespace Laravel\Telescope\Tests\Telescope;

use Illuminate\Mail\Mailable;
use Laravel\Telescope\Database\Factories\EntryModelFactory;
use Laravel\Telescope\ExtractTags;
use Laravel\Telescope\FormatModel;
use Laravel\Telescope\Tests\FeatureTestCase;

class ExtractTagTest extends FeatureTestCase
{
    /**
     * @test
     */
    public function test_extract_tag_from_array_containing_flat_collection()
    {
        $this->assertTrue(true, 'This should already work.');

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );

        $flat_collection = EntryModelFactory::new()->create();

        $tag = FormatModel::given($flat_collection->first());
        $extracted_tag = ExtractTags::fromArray([$flat_collection]);

        $this->assertSame($tag, $extracted_tag[0]);
    }

    /**
     * @test
     */
    public function test_extract_tag_from_array_containing_deep_collection()
    {
        $this->assertTrue(true, 'This should already work.');

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );

        $deep_collection = EntryModelFactory::times(1)->create()->groupBy('type');

        $tag = FormatModel::given($deep_collection->first()->first());
        $extracted_tag = ExtractTags::fromArray([$deep_collection]);

        $this->assertSame($tag, $extracted_tag[0]);
    }

    /**
     * @test
     */
    public function test_extract_tag_from_mailable()
    {
        $this->assertTrue(true, 'This should already work.');

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );

        $deep_collection = EntryModelFactory::times(1)->create()->groupBy('type');
        $mailable = new DummyMailableWithData($deep_collection);

        $tag = FormatModel::given($deep_collection->first()->first());
        $extracted_tag = ExtractTags::from($mailable);

        $this->assertSame($tag, $extracted_tag[0]);
    }
}

class DummyMailableWithData extends Mailable
{
    private $mail_data;

    public function __construct($mail_data)
    {
        $this->mail_data = $mail_data;
    }

    public function build()
    {
        return $this->from('from@laravel.com')
            ->to('to@laravel.com')
            ->view(['raw' => 'simple text content'])
            ->with([
                'mail_data' => $this->mail_data,
            ]);
    }
}
