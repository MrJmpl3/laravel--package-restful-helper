<?php

use Illuminate\Support\Facades\DB;
use MrJmpl3\LaravelRestfulHelper\Tests\Models\TestModel;

test('check sorts', function () {
    TestModel::factory()->count(500)->create();

    DB::enableQueryLog();

    $response = $this->getJson('/testing-legacy-collection-string?sort=nick,-is_active')->assertOk();

    DB::disableQueryLog();

    $this->assertEquals('select * from `test_models` order by `name` asc, `is_active` desc limit 15 offset 0', collect(DB::getQueryLog())->pluck('query')->last());
    $this->assertEquals(1, $response['current_page']);
    $this->assertCount(15, $response['data']);
    $this->assertEquals('http://localhost/testing-legacy-collection-string?sort=nick%2C-is_active&page=1', $response['first_page_url']);
    $this->assertEquals(1, $response['from']);
    $this->assertEquals(34, $response['last_page']);
    $this->assertEquals('http://localhost/testing-legacy-collection-string?sort=nick%2C-is_active&page=34', $response['last_page_url']);
    $this->assertCount(15, $response['links']);
    $this->assertEquals('http://localhost/testing-legacy-collection-string?sort=nick%2C-is_active&page=2', $response['next_page_url']);
    $this->assertEquals('http://localhost/testing-legacy-collection-string', $response['path']);
    $this->assertEquals(15, $response['per_page']);
    $this->assertEquals(null, $response['prev_page_url']);
    $this->assertEquals(15, $response['to']);
    $this->assertEquals(500, $response['total']);
});
