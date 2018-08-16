<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserContacts extends Model
{
  protected $table = 'user_contacts';
  protected $fillable = [
    'user_id', 'contact_id', 'status',
  ];
}
