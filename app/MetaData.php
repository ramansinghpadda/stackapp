<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MetaData extends Model
{
    //
    protected $table = 'meta_data';

    protected $fillable=['mmID','appID','value'];

}
