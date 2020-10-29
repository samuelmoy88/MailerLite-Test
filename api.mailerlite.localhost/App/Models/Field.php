<?php


namespace App\Models;

use Core\BaseModel;

class Field extends BaseModel
{
    const TYPE_DATE = 1;

    const TYPE_NUMBER = 2;

    const TYPE_STRING = 3;

    const TYPE_BOOLEAN = 4;

    protected string $table = 'fields';

    protected array $fillable = [];

    protected array $typeLabels = [
        self::TYPE_DATE => 'Date',
        self::TYPE_NUMBER => 'Number',
        self::TYPE_STRING => 'String',
        self::TYPE_BOOLEAN => 'Boolean',
    ];

    protected array $types = [
        self::TYPE_DATE,
        self::TYPE_NUMBER,
        self::TYPE_STRING,
        self::TYPE_BOOLEAN
    ];

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        if ($this->id) {
            $this->setAttribute('type_label', $this->typeLabels[$this->type]);
            $this->isBeingUsed();
        }
    }

    public function isBeingUsed()
    {
        $field = SubscriberField::find(['field_id' => $this->id]);

        $isUsed = $field ? true : false;

        $this->setAttribute('is_used', $isUsed);

        return $field ? true : false;
    }
}