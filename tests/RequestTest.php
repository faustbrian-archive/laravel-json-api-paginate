<?php

declare(strict_types=1);

it('will discover the page size parameter')
    ->get('/?page[size]=2')
    ->assertJsonFragment(['per_page' => 2]);

it('will discover the page number parameter')
    ->get('/?page[number]=2')
    ->assertJsonFragment(['current_page' => 2]);

it('will use the default page size')
    ->get('/')
    ->assertJsonFragment(['per_page' => 30]);

it('will use the configured page size parameter')
    ->config(['json-api-paginate.size_parameter' => 'modified_size'])
    ->get('/?page[modified_size]=2')
    ->assertJsonFragment(['per_page' => 2]);

it('will use the configured page number parameter')
    ->config(['json-api-paginate.number_parameter' => 'modified_number'])
    ->get('/?page[modified_number]=2')
    ->assertJsonFragment(['current_page' => 2]);
