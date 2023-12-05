<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    
    use HasFactory;

    protected $casts = [
        'start_date' => 'datetime:d.m.Y'
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function getStartDateFormattedAttribute() {
        return date( 'd.m.Y', strtotime( $this->start_date ) );
    }

}
