<?php

// app/Models/Kategori.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';

    protected $fillable = ['nama_kategori', 'status'];

    public function multimedia()
    {
        return $this->hasMany(\App\Models\Multimedia::class, 'kategori_id');
    }
}

