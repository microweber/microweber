<?php


namespace MicroweberPackages\Database\Traits;


trait CreatedByTrait
{

    protected $userStamps = [


       // This userstamp should be updated when an event is
       // invoked i.e 'creating','updating','deleting','saving'.
       'created_by' => [
            'depends_on_event' => 'creating',
       ],
       'deleted_by' => [
             'depends_on_event' => 'deleting',
       ],

       // This userstamp should be set if "is_archived" is dirty (has some changes in value)
       'archived_by' => [
            'depends_on_field' => 'is_archived'
       ],

       // This userstamp should be set if "updating" event is invoked on this model,
       // and "is_submitted" is dirty (has some changes in value)
       'submitted_by'=> [
            'depends_on_event' => 'updating',
            'depends_on_field' => 'is_submitted'
       ],

       // This userstamp should be set if "updating" event is invoked on this model,
       // and provided expression evaluates to true
       'suspended_by' => [
          'depends_on_event' => 'updating',
          // $api_hits is a model field i.e $model->api_hits
          'depends_on_expression' => '$api_hits > 100'
       ]

    ];


    /**
     * Store the relations
     *
     * @var array
     */
    private static $dynamic_relations = [];


    // Contains the userstamp fields which depend on a model event
    // Contains the userstamp fields which depends upon certain expressions
    // Contains the userstamp fields which depend on a some other field ( which should be dirty in this case )
    private $userstampsToInsert = [];

    // events to capture
    protected static $CREATING = 'creating';
    protected static $SAVING = 'saving';
    protected static $UPDATING = 'updating';
    protected static $DELETING = 'deleting';

    public static function bootUserStampTrait()
    {
        $self = new static();

        static::creating(function ($model) use ($self) {
            $self->setUserstampOnModel($model, self::$CREATING);
        });

        static::updating(function ($model) use ($self) {
            $self->setUserstampOnModel($model, self::$UPDATING);
        });

        static::saving(function ($model) use ($self) {
            if (!empty($model->id)) {

                $self->setUserstampOnModel($model, self::$SAVING);
            }
        });

        static::deleting(function ($model) use ($self) {
            $self->setUserstampOnModel($model, self::$DELETING);
        });

        $class = $self->getUserClass();
        foreach ($self->getUserstampFields() as $field) {
            $name = $self->getRelationName("{$field}_user");
            $self->addDynamicRelation($name, function ($self) use ($field, $class) {
                return $self->belongsTo($class, $field);
            });
        }

    }

    /**
     * Set userstamp on the current model depending upon the
     * 1. Event
     * 2. Field
     * 3. Expression
     * @param $model
     * @param string $eventName
     */
    public function setUserstampOnModel(&$model, $eventName = '')
    {
        foreach ($this->userstamps as $fieldName => $dependsOn) {
            if (is_array($dependsOn) && count($dependsOn) > 0) {

                $mathes = [
                    'depends_on_event' => $this->dependsOnEvent($dependsOn, $eventName),
                    'depends_on_field' => $this->dependsOnField($dependsOn, $model),
                    'depends_on_expression' => $this->dependsOnExpression($dependsOn, $model)
                ];

                // check if all given conditions were met.
                $shouldApplyUserstamp = collect($dependsOn)->every(function ($value, $key) use ($mathes) {
                    return $mathes[$key];
                });

                // set userstamp
                if ($shouldApplyUserstamp) {
                    $model->{$fieldName} = auth()->id();
                }

                // In case of a model, which is being soft deleted, we need to save it with applied userstamp before proceeding.
                if ($eventName == self::$DELETING && $this->isSoftDeleteEnabled() && !empty($model->{$fieldName})) {
                    $model->save();
                }
            }
        }
    }

    /***
     * Check if given userstamp depends on certain field, and field has been modified.
     * @param $dependsOn
     * @param $model
     * @return bool
     */
    private function dependsOnField($dependsOn, $model)
    {
        if (empty($dependsOn['depends_on_field']))
            return false;

        return $model->isDirty($dependsOn['depends_on_field']);
    }

    /**
     * Check if given userstamp depends on certain event.
     * @param $dependsOn
     * @param $eventName
     * @return bool
     */
    private function dependsOnEvent($dependsOn, $eventName)
    {
        if (empty($dependsOn['depends_on_event']))
            return false;

        // if userstamp depends on one or more than one events, i.e provided in array format
        if (is_array($dependsOn['depends_on_event'])) {
            return in_array($eventName, $dependsOn['depends_on_event']);
        }
        // if userstamp depends on only one event, provides as string
        return $dependsOn['depends_on_event'] == $eventName;
    }

    /***
     * Check if given userstamp depends on certain expression, and expression evaluates to True.
     * @param $dependsOn
     * @param $model
     * @return bool|mixed
     */
    private function dependsOnExpression($dependsOn, $model)
    {
        if (empty($dependsOn['depends_on_expression']))
            return false;

        $expression = $dependsOn['depends_on_expression'];
        $pattern = '/\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/';
        $matchCount = preg_match_all($pattern, $expression, $matches);
        for ($i = 0; $i < $matchCount; $i++) {
            $expression = str_replace($matches[0][$i], '"' . (empty($model->{$matches[1][$i]}) ? null : $model->{$matches[1][$i]}) . '"', $expression);
        }
        $expression = "return " . $expression . ";";
        return eval($expression);
    }

    /***
     *  Check if 'this' model uses the soft deletes trait
     * @return bool
     */
    public function isSoftDeleteEnabled()
    {
        return in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this)) && !$this->forceDeleting;
    }


    /***
     * Get userstamp field names from the userstamp array
     * @return mixed
     */
    public function getUserstampFields()
    {
        return collect($this->userstamps)->map(function ($v, $k) {
            return is_array($v) ? $k : $v;
        })->values()->toArray();
    }


    /**
     * Create a relation name from the given userstamp field name
     * @param $userstamp
     * @return string
     */
    protected function getRelationName($userstamp)
    {
        return lcfirst(join(array_map('ucfirst', explode('_', $userstamp))));
    }

    /**
     * Add a new relation
     *
     * @param $name
     * @param $closure
     */
    public static function addDynamicRelation($name, $closure)
    {
        static::$dynamic_relations[$name] = $closure;
    }

    /**
     * Determine if a relation exists in dynamic relationships list
     * @param $name
     * @return bool
     */
    public static function hasDynamicRelation($name)
    {
        return array_key_exists($name, static::$dynamic_relations);
    }

    /**
     * If the key exists in relations then
     * return call to relation or else
     * return the call to the parent
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (static::hasDynamicRelation($name)) {
            // check the cache first
            if ($this->relationLoaded($name)) {
                return $this->relations[$name];
            }
            // load the relationship
            return $this->getRelationshipFromMethod($name);
        }
        return parent::__get($name);
    }

    /**
     * Override the default __call() method for query builder
     * It dynamically handle calls into the query instance.
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (static::hasDynamicRelation($method)) {
            return call_user_func(static::$dynamic_relations[$method], $this);
        }
        return parent::__call($method, $parameters);
    }


    /**
     * Get the class being used to provide a User.
     * @return string
     */
    protected function getUserClass()
    {
        if (get_class(auth()) === 'Illuminate\Auth\Guard') {
            return auth()->getProvider()->getModel();
        }
        return auth()->guard()->getProvider()->getModel();
    }


    /**
     * Model scope to load userstamp relations like Model:withUserstamps()->get()
     * @param $query
     * @return mixed
     */
    public function scopeWithUserstamps($query)
    {
        $query->with(array_keys(static::$dynamic_relations));
        return $query;
    }


}