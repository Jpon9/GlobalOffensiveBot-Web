<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogMessage extends Model
{
    protected $table = 'log';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['page', 'note', 'user'];
}
