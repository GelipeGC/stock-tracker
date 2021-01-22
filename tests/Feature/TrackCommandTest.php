<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Retailer;
use App\Clients\StockStatus;
use Facades\App\Clients\ClientFactory;
use RetailerWithProductSeeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TrackCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_tracks_product_stock()
    {

        $this->seed(RetailerWithProductSeeder::class);

        $this->assertFalse(Product::first()->inStock());

        ClientFactory::shouldReceive('make->checkAvailability')->andReturn(
            new StockStatus($available = true, $price = 29900)
        );
        //when
        $this->artisan('track')
            ->expectsOutput('All done!');
        //then
        $this->assertTrue(Product::first()->inStock());
    }
}
