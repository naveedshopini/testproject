<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
     protected $fillable = ['ticket_id','user_id','subject','description','priority','category','owner','status','agent'];
}
