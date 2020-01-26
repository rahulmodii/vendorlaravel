<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Country;
class Vendor extends Model
{
    protected $fillable=['vendor_name','first_name','last_name','country','address','image','pdfdocument'];
    public function countryr()
    {
        return $this->hasOne(Country::class,'id','country');
    }
}
