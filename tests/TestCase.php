<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected static $classMigrationsRun = false;
    // overload to refresh database for every test
    protected $refreshEveryTest = false;

    public static function setUpBeforeClass()
    {
        // This fixes the PHPUnit_Framework_Constraint error with the inDatabase test helpers
        if (!class_exists('PHPUnit_Framework_Constraint')) {
            class_alias(\PHPUnit\Framework\Constraint\Constraint::class, 'PHPUnit_Framework_Constraint');
        }
    }

    public function setUp()
    {
        parent::setUp();
        if ($this->refreshEveryTest || !static::$classMigrationsRun) {
            $this->refreshMigrations();
        }
    }
    protected function refreshMigrations()
    {
        \Log::info(get_class($this) . " refreshing migrations");
        $this->artisan('migrate:refresh');
        static::$classMigrationsRun = true;
    }

    /**
     * Model Events are used by us to generate UUIDS etc.
     *
     * Faking Events _also_ fakes model events. Which is a pain.
     * This works around that.
     */
    public function safelyFakeEvents()
    {
        // avoid breaking model events.
        $modelDispatcher = Event::getFacadeRoot();
        Event::fake();
        Model::setEventDispatcher($modelDispatcher);
    }
}
