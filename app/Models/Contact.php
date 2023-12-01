<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    use HasFactory;

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function getContactAddressAttribute() {
        
        $address = $this->address;
        
        if( $this->postcode ) {
            $address .= ', ' . $this->postcode;
        }
        
        if( $this->city ) {
            $address .= ' ' . $this->city;
        }
        
        return $address;

    }
}
