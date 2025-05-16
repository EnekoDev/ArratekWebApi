<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "customers";
    protected $fillable = ["name", "address", "phone", "maintenance"];
    protected $hidden = ["created_at", "updated_at"];

    public function invoice() {
        return $this->hasMany(Invoice::class);
    }

    public function ticket() {
        return $this->hasMany(Ticket::class);
    }

    public function manteinance() {
        return $this->belongsTo(Manteinance::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
