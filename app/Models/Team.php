<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['tournament_id', 'name', 'contact_person', 'status'];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
}
