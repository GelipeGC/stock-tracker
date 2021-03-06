<?php

namespace App\Models;

use Illuminate\Support\Facades\Http;


class Stock extends Model
{
    protected $table = 'stock';

    protected $casts = [
        'in_stock' => 'boolean'
    ];

    public function track()
    {
        $status = $this->retailer
                ->client()
                ->checkAvailability($this);

        $this->update([
            'in_stock' => $status->available,
            'price' => $status->price
        ]);

    }

    public function retailer()
    {
        return $this->belongsTo(Retailer::class);
    }

}
