<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $bexio_api_scopes = [
        'accounting'        => 'Write access to accounting data',
        'article_show'      => 'Read access to items / products',
        'article_edit'      => 'Write access to items / products',
        'bank_account_show' => 'Show bank accounts',
        'bank_payment_show' => 'Show bank payments',
        'bank_payment_edit' => 'Show and edit bank payments',
        'contact_show'      => 'Read access to contacts',
        'contact_edit'      => 'Write access to contacts',
        'email'             => 'Read access to the email address of the signed in user',
        'file'              => 'Read and write access to the inbox (file upload)',
        'kb_invoice_show'   => 'Read access to invoices',
        'kb_invoice_edit'   => 'Write access to invoices',
        'kb_offer_show'     => 'Read access to quotes',
        'kb_offer_edit'     => 'Write access to quotes',
        'kb_order_show' 	=> 'Read access to orders',
        'kb_order_edit' 	=> 'Write access to orders',
        'monitoring_show' 	=> 'Read access to timesheets',
        'monitoring_edit' 	=> 'Write access to timesheets',
        'note_show' 	    => 'Read access to contact notes',
        'note_edit' 	    => 'Write access to contact notes',
        'offline_access' 	=> 'Offline access. This scope is needed to obtain a refresh token.',
        'openid' 	        => 'Standard OpenID Connect (OIDC) scope. Required to indicate that the application intends to use OIDC to verify the user\'s identity',
        'kb_article_order_show' => 'Read access to purchase orders',
        'kb_article_order_edit' => 'Write access to purchase orders',
        'profile' 	        => 'Read access to the user\'s name',
        'project_show' 	    => 'Read access to projects',
        'project_edit' 	    => 'Write access to projects',
        'stock_edit' 	    => 'Write access to item stock',
        'task_show' 	    => 'Read access to tasks',
        'task_edit' 	    => 'Write access to tasks',
        'kb_bill_show' 	    => 'Read access to supplier bills',
        'kb_expense_show' 	=> 'Read access to Purchase Expenses'
    ];

    public function user_metas(): HasMany
    {
        return $this->hasMany(UserMeta::class);
    }

    public function getUserScopes() {
        return $this->bexio_api_scopes;
    }

    public function getUserScopesAttribute() {
        
        $user_scopes = UserMeta::where(['user_id' => $this->id, 'meta_key' => 'bexio_scopes'])->first();
        $user_scopes = $user_scopes && isset( $user_scopes->meta_value ) ? (array) json_decode( $user_scopes->meta_value ) : [];

        return array_flip( $user_scopes );
        
    }

    public function getBexioAccessTokenAttribute() {
        return UserMeta::where(['user_id' => $this->id, 'meta_key' => 'bexio_access_token'])->value('meta_value');
    }

    public function getBexioRefreshTokenAttribute() {
        return UserMeta::where(['user_id' => $this->id, 'meta_key' => 'bexio_refresh_token'])->value('meta_value');
    }
    
}
