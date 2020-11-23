<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
  protected $table = "histories";

  protected $fillable = [
      'user_id', 'title', 'data', 'comment', 'array', 'array_url'
  ];


  public function getJWTIdentifier(){
      return $this->getKey();
  }

  public function getJWTCustomClaims(){
      return [];
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
