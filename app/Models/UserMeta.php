<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class UserMeta extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getUserMeta( int $user_id, string $key ) {
        return self::where([ 'user_id' => $user_id, 'meta_key' => $key ])->value('meta_value');
    }

    public static function updateUserMeta( int $user_id, string $key, string $value ) {

        $exists = self::where( [ 'user_id' => $user_id, 'meta_key' => $key ] )->first();
        if( $exists ) {
            $exists->meta_value = $value;
            $exists->save();
            return;
        }

        $meta = new UserMeta;

        $meta->user_id = $user_id;
        $meta->meta_key = $key;
        $meta->meta_value = $value;

        $meta->save();
        
    }
    
}
