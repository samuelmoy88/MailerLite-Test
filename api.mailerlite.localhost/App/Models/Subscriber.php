<?php


namespace App\Models;

use Core\BaseModel;

class Subscriber extends BaseModel
{
    const STATE_ACTIVE = 1;
    
    const STATE_UNSUBSCRIBED = 2;
    
    const STATE_JUNK = 3;
    
    const STATE_BOUNCED = 4;
    
    const STATE_UNCONFIRMED = 5;
    
    protected string $table = 'subscribers';

    protected array $fillable = [];

    protected array $stateLabels = [
        self::STATE_ACTIVE => 'Active',
        self::STATE_UNSUBSCRIBED => 'Unsubscribed',
        self::STATE_JUNK => 'Junk',
        self::STATE_BOUNCED => 'Bounced',
        self::STATE_UNCONFIRMED => 'Unconfirmed',
    ];

    protected array $states = [
        self::STATE_ACTIVE,
        self::STATE_UNSUBSCRIBED,
        self::STATE_JUNK,
        self::STATE_BOUNCED,
        self::STATE_UNCONFIRMED,
    ];

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        if ($this->id) {
            $this->fields();

            $this->setAttribute('state_label', $this->stateLabels[$this->state]);
        }
    }

    public function fields()
    {
        $this->setAttribute('fields', SubscriberField::findAll(
            ['subscriber_id' => $this->id]
        ) ?: []);

    }

    public function addField($fields = [])
    {
        $subscriberFields = new SubscriberField($fields);

        $subscriberFields->create();
    }
}