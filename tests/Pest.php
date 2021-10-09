<?php

declare(strict_types=1);

use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Route;
use Tests\TestModel;

uses(Tests\TestCase::class)->beforeEach(function () {
    Carbon::setTestNow(Carbon::create('2017', '1', '1', '1', '1', '1'));

    // Configuration
    $this->app['config']->set('database.default', 'sqlite');
    $this->app['config']->set('database.connections.sqlite', [
        'driver'   => 'sqlite',
        'database' => ':memory:',
        'prefix'   => '',
    ]);

    $this->app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');

    // Database
    $this->app['db']->connection()->getSchemaBuilder()->create('test_models', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name');
        $table->rememberToken();
        $table->timestamps();
    });

    foreach (range(1, 40) as $index) {
        TestModel::create(['name' => "user{$index}"]);
    }

    // Routes
    Route::any('/', function () {
        return TestModel::jsonPaginate();
    });
})->in('.');
