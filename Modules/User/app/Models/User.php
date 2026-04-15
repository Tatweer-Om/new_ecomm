<?php

namespace Modules\User\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory; // ✅ HasApiTokens must be here

    protected $connection = 'tenant';  // tenant DB
    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'contact',
        'password',
        'dashboard', 'user', 'booking', 'customer', 'expense',
        'dress', 'laundry', 'setting', 'add_dress', 'delete_booking',
        'add_date',
        'added_by',
        'update_date',
        'updated_by',
        'branch_id',
        'created_at',
        'updated_at'
    ];

    protected $hidden = ['password', 'remember_token'];
}
