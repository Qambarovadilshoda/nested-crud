<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
        'icon'
    ];
    public function parent(){
        return $this->belongsTo(Folder::class, 'parent_id');
    }
    public function children(){
        return $this->hasMany(Folder::class, 'parent_id');
    }
}
