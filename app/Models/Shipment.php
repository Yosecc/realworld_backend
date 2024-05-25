<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $hidden = ["id","shipment_id","order_id","tracker_id","tracker_created_at","tracker_signed_by","label_date","label_url","rate_service","rate_price","rate_retail_rate","created_at","updated_at"];
}
