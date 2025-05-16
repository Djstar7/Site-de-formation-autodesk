<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentModel extends Model
{
    use HasFactory;

    protected $table = 'payment';

    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(UsersoneModel::class);
    }
}
