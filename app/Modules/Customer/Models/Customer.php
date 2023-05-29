<?php
namespace App\Modules\Customer\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Modules\Customer\Contracts\Customer as CustomerContract;
use Illuminate\Notifications\Notifiable;


class Customer extends Authenticatable implements  CustomerContract
{
    use HasFactory, Notifiable;

    protected $table = 'customers';

    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'email',
        'phone',
        'password',
        'api_token',
        'token',
        'customer_group_id',
        'subscribed_to_news_letter',
        'status',
        'is_verified',
        'is_suspended',
        'notes',
    ];

    protected $hidden = [
        'password',
        'api_token',
        'remember_token',
    ];


    public function all_orders()
    {
        // return $this->hasMany(OrderProxy::modelClass(), 'customer_id');
    }




}
