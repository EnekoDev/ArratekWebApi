<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $table = "tickets";
    protected $fillable = ["title", "description", "answer", "resolved_on", "customer_id", "invoice_id"];
    protected $hiddent = ["updated_at"];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function invoice() {
        return $this->belongsTo(Invoice::class);
    }
}
