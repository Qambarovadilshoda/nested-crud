<?php

namespace App\Models;

use App\Models\Attachment;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    public function icon(){
        return $this->morphOne(Attachment::class, 'attachmentable');
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
