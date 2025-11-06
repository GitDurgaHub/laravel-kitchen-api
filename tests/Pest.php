<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

uses(
    Tests\TestCase::class,
    RefreshDatabase::class,
    WithFaker::class
)->in('Feature', 'Unit');
