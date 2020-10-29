<?php


namespace App\Models;

use Core\BaseModel;


class SubscriberField extends BaseModel
{
    protected string $table = 'subscribers_fields';

    protected array $fillable = [];

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }
}