<?php

declare(strict_types=1);

use Tests\TestModel;

it('can paginate records', function (): void {
    $paginator = TestModel::jsonPaginate();

    $this->assertSame('http://localhost?page%5Bnumber%5D=2', $paginator->nextPageUrl());
});

it('returns the amount of records specified in the config file', function (): void {
    config()->set('json-api-paginate.default_size', 10);

    $paginator = TestModel::jsonPaginate();

    $this->assertCount(10, $paginator);
});

it('can return the specified amount of records', function (): void {
    $paginator = TestModel::jsonPaginate(15);

    $this->assertCount(15, $paginator);
});

it('will not return more records that the configured maximum', function (): void {
    $paginator = TestModel::jsonPaginate(15);

    $this->assertCount(15, $paginator);
});

it('can set a custom base url in the config file', function (): void {
    config()->set('json-api-paginate.base_url', 'https://example.com');

    $paginator = TestModel::jsonPaginate();

    $this->assertSame('https://example.com?page%5Bnumber%5D=2', $paginator->nextPageUrl());
});

it('can use simple pagination', function (): void {
    config()->set('json-api-paginate.use_simple_pagination', true);

    $paginator = TestModel::jsonPaginate();

    $this->assertFalse(method_exists($paginator, 'total'));
});
