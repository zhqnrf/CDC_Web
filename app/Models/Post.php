<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory, Uuids;

    protected $table = 'post';


    protected $fillable = [
        'user_id',
        'link_apply',
        'description',
        'company',
        'position',
        'expired',
        'image',
        'can_comment',
        'verivied',
        'type_jobs',
        'admin_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, "admin_id");
    }

}