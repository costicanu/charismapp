<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table='projects';

    public function customer(){
        #    return $this->hasOne('\App\Project');
    }

}
