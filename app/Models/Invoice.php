<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Invoice extends Model
{
    use HasFactory;
    protected $table = "invoices";
    protected $fillable = ["number", "date", "total_time", "total_price", "tax", "customer_id"];
    protected $hidden = ["created_at", "updated_at"];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function ticket() {
        return $this->hasMany(Ticket::class);
    }
}
