<?php

declare(strict_types=1);

it('will discover the page size parameter', function (): void {
    $response = $this->get('/?page[size]=2');

    $response->assertJsonFragment(['per_page' => 2]);
});

it('will discover the page number parameter', function (): void {
    $response = $this->get('/?page[number]=2');

    $response->assertJsonFragment(['current_page' => 2]);
});

it('will use the default page size', function (): void {
    $response = $this->get('/');

    $response->assertJsonFragment(['per_page' => 30]);
});

it('will use the configured page size parameter', function (): void {
    config(['json-api-paginate.size_parameter' => 'modified_size']);

    $response = $this->get('/?page[modified_size]=2');

    $response->assertJsonFragment(['per_page' => 2]);
});

it('will use the configured page number parameter', function (): void {
    config(['json-api-paginate.number_parameter' => 'modified_number']);

    $response = $this->get('/?page[modified_number]=2');

    $response->assertJsonFragment(['current_page' => 2]);
});
