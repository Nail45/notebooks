<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedBacks extends Model
{
  protected $table = 'feedbacks';

  protected $fillable = [
    'rating',
    'user_id',
    'notebook_id',
    'advantage',
    'disadvantages',
    'summary'
  ];

  public function notebook()
  {
    return $this->belongsTo(Notebook::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
