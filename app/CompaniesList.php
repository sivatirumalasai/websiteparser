<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompaniesList extends Model
{
    protected $fillable = [
        'company_name', 'cin','company_link', 'company_class','status'
    ];
}
