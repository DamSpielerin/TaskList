<?php

use Illuminate\Database\Eloquent\Model as Model;

class AdminSession extends Model
{
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';

    protected $table = 'admin_session';
    protected $fillable = ['session_id', 'login', 'is_logged'];
    protected $attributes = [
        'is_logged' => false
    ];
}
