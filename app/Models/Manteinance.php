<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manteinance extends Model
{
    protected $table = "manteinances";
    protected $fillable = ["name"];
    protected $hidden = ["created_at", "updated_at"];

    public function customer() {
        return $this->hasMany(Customer::class);
    }
}
