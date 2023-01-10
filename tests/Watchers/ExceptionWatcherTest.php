<?php

namespace Laravel\Telescope\Tests\Watchers;

use Error;
use ErrorException;
use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Laravel\Telescope\EntryType;
use Laravel\Telescope\Tests\FeatureTestCase;
use Laravel\Telescope\Watchers\ExceptionWatcher;
use ParseError;

class ExceptionWatcherTest extends FeatureTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app->get('config')->set('logging.default', 'syslog');

        $app->get('config')->set('telescope.watchers', [
            ExceptionWatcher::class => true,
        ]);
    }

    public function test_exception_watcher_register_entries()
    {
        $this->assertTrue(true, 'This should already work.');

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );

        $handler = $this->app->get(ExceptionHandler::class);

        $exception = new BananaException('Something went bananas.');

        $handler->report($exception);

        $entry = $this->loadTelescopeEntries()->first();

        $this->assertSame(EntryType::EXCEPTION, $entry->type);
        $this->assertSame(BananaException::class, $entry->content['class']);
        $this->assertSame(__FILE__, $entry->content['file']);
        $this->assertSame(31, $entry->content['line']);
        $this->assertSame('Something went bananas.', $entry->content['message']);
        $this->assertArrayHasKey('trace', $entry->content);
    }

    public function test_exception_watcher_register_throwable_entries()
    {
        $this->assertTrue(true, 'This should already work.');

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );

        $handler = $this->app->get(ExceptionHandler::class);

        $exception = new BananaError('Something went bananas.');

        $handler->report($exception);

        $entry = $this->loadTelescopeEntries()->first();

        $this->assertSame(EntryType::EXCEPTION, $entry->type);
        $this->assertSame(BananaError::class, $entry->content['class']);
        $this->assertSame(__FILE__, $entry->content['file']);
        $this->assertSame(49, $entry->content['line']);
        $this->assertSame('Something went bananas.', $entry->content['message']);
        $this->assertArrayHasKey('trace', $entry->content);
    }

    public function test_exception_watcher_register_entries_when_eval_failed()
    {
        $this->assertTrue(true, 'This should already work.');

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );

        $handler = $this->app->get(ExceptionHandler::class);

        $exception = null;

        try {
            eval('if (');

            $this->fail('eval() was expected to throw "syntax error, unexpected end of file"');
        } catch (ParseError $e) {
            // PsySH class ExecutionLoopClosure wraps ParseError in an exception.
            $exception = new ErrorException($e->getMessage(), $e->getCode(), 1, $e->getFile(), $e->getLine(), $e);
        }

        $handler->report($exception);

        $entry = $this->loadTelescopeEntries()->first();

        $this->assertSame(EntryType::EXCEPTION, $entry->type);
        $this->assertSame(ErrorException::class, $entry->content['class']);
        $this->assertStringContainsString("eval()'d code", $entry->content['file']);
        $this->assertSame(1, $entry->content['line']);

        if (\PHP_VERSION_ID < 80000) {
            $this->assertSame('syntax error, unexpected end of file', $entry->content['message']);
        } else {
            $this->assertSame("Unclosed '('", $entry->content['message']);
        }

        $this->assertArrayHasKey('trace', $entry->content);
    }
}

class BananaException extends Exception
{
    //
}

class BananaError extends Error
{
    //
}
