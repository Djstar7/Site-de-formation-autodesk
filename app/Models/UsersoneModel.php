<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
class UsersoneModel extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = 'usersone';
    protected $fillable = ['name_usersone', 'email_usersone', 'password_usersone'];
    protected $hidden = ['password_usersone']; // pour cacher le mot de passe en JSON
    public $timestamps = false;

}
