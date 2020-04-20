<?php

use Illuminate\Database\Eloquent\Model as Model;


class Task extends Model
{
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';

    protected $table = 'task';

    protected $fillable = ['username', 'email', 'description', 'done','was_edited'];

    protected $attributes = [
        'done' => false
    ];
    public static function validate($form, $isEditForm = false)
    {
        $errors = '';
        $pattern = '/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/';
        $required = ['username', 'email', 'description'];

        if ($isEditForm) {
            array_push($required, 'id', 'done');
        }
        foreach ($required as $field_name) {
            if (!array_key_exists($field_name, $form) || $form[$field_name] === '') {
                $errors .= "Field {$field_name} is empty.<br>";
            }
        }
        if (!preg_match($pattern, $form['email'])) {
            $errors .= 'Email has wrong format';
        }
        return $errors;
    }

}
