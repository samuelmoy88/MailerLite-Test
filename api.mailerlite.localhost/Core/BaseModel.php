<?php


namespace Core;

use Exception;

abstract class BaseModel
{
    public array $attributes = [];

    protected array $fillable = [];

    protected string $table;

    protected bool $exists = false;

    protected string $error_message;

    private array $attributesInSchema = [];

    /**
     * BaseModel constructor.
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        $this->getAttributesFromSchema();

        $this->fill($attributes);

        return $this;
    }

    /**
     * Create a model
     * @return bool
     */
    protected function create()
    {
        //Filter only attributes in the schema
        $this->filterAttributesFromSchema();

        DB()->insert($this->table, $this->attributes);

        $id = DB()->id();

        $return = false;

        if ($id) {

            $this->id = $id;

            $return = true;

        }else {
            $this->setErrorMessage(DB()->error()[2]);
        }

        return $return;
    }

    /**
     * Update a model
     * @param array $condition
     * @return bool|int
     */
    protected function update($condition = [])
    {
        if (!$condition) {

            $condition = ["id" => $this->id];

        }

        //Filter only attributes in the schema
        $this->filterAttributesFromSchema();

        $update = DB()->update(
            $this->table,
            $this->attributes,
            $condition
        );

        return $update;
    }

    /**
     * Delete a model
     * @param array $condition
     * @return bool
     */
    protected function delete($condition = [])
    {
        if (!$condition) {

            $condition = ["id"  => $this->id];

        }

        return DB()->delete($this->table, $condition);
    }

    /**
     * Manually creates a model without necessarily having one instantiated
     * @param string $table
     * @param array $attributes
     * @return bool|int
     */
    protected function createModel(string $table, $attributes = [])
    {
        $insert = DB()->insert($table, $attributes);

        if ($insert == false) {

            $this->setErrorMessage(DB()->error()[2]);

            return false;
        }

        return DB()->id();

    }

    /**
     * @param string $table
     * @param array $attributes
     * @param array $condition
     * @return bool|int
     */
    protected function updateModel(string $table, $attributes = [], $condition = [])
    {
        $update = DB()->update($table, $attributes, $condition);

        if ($update === false) {

            $this->setErrorMessage(DB()->error()[2]);

            return false;
        }

        return true;
    }

    /**
     * @param string $table
     * @param array $condition
     * @return bool
     */
    protected function deleteModel(string $table, $condition = [])
    {
        $delete = DB()->delete($table, $condition);

        if ($delete == false) {

            $this->setErrorMessage(DB()->error()[2]);

            return false;
        }

        return $delete;
    }

    /**
     * Return the last inserted id
     * @return mixed
     */
    protected function lastInsertId()
    {
        return DB()->id();
    }

    /**
     * Update or create a model
     * @param array $attributes
     * @param array $condition
     * @return bool|int|mixed
     */
    public static function updateOrCreate($attributes = [], $condition = [])
    {

        $instance = static::find($condition);

        $instance->fill($attributes);

        if ($instance->exists) {

            $result = $instance->update();
            $result = $result === true ? $instance->id : false;

        }else{

            $result = $instance->create();
            $result = $result === true ? $instance->lastInsertId() : false;
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->error_message;
    }

    /**
     * @param string $error_message
     */
    public function setErrorMessage($error_message)
    {
        $this->error_message = $error_message;
    }

    /**
     * Set attribute on object
     * @param $key
     * @param $value
     */
    protected function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Set attribute on object
     * @param $key
     * @return bool
     */
    protected function isAttributeEmpty($key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * Determine if the given attribute may be mass assigned
     * @param $key
     * @return bool
     */
    protected function isFillable($key)
    {
        if (
            (
                in_array($key, $this->fillable) ||
                $this->fillable === []
            ) &&
            array_key_exists($key, $this->attributes)
        )
            return true;
    }


    protected function fillableFromArray(array $attributes)
    {
        if (count($this->fillable) > 0) {
            return array_intersect_key(
                $attributes,
                array_flip($this->attributesInSchema)
            );
        }

        return $attributes;
    }

    /**
     * @return $this
     */
    private function getAttributesFromSchema()
    {
        $query = "SELECT * 
                    FROM information_schema.COLUMNS 
                    WHERE table_schema = 'mailerlite' 
                        AND table_name = '{$this->table}'";

        $columns = DB()->query($query)->fetchAll();

        foreach ($columns as $column) {
            $_column = $column['COLUMN_DEFAULT'];

            switch ($_column) {
                case 'current_timestamp()':
                case 'CURRENT_TIMESTAMP':
                    $_column = date('Y-m-d H:i:s');
                    break;
                case 'NULL':
                    $_column = null;
                    break;
            }

            $this->setAttribute($column['COLUMN_NAME'], $_column);

            $this->attributesInSchema[] = $column['COLUMN_NAME'];
        }

        return $this;
    }

    /**
     * Fill the entity with an array of attributes.
     * @param array $attributes
     * @return static
     */
    protected function fill($attributes)
    {
        if (is_array($attributes)) {
            foreach ($this->fillableFromArray($attributes) as $key => $value) {
                if ($this->isFillable($key)) {
                    $this->setAttribute($key, $value);
                }
            }
        }

        return $this;
    }

    /**
     * Get all the model's attributes
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Dynamically get an attribute
     * @param $key
     * @return mixed
     * @throws Exception
     */
    public function __get($key)
    {
        try{
            if (isset($this->attributes[$key])) {
                if ($this->attributes[$key] != 'null') {
                    return $this->attributes[$key];
                }
                return null;
            }
            throw new Exception("{$key} is not a valid property");

        } catch (Exception $e) {
//            Log::log($e->getMessage());
        }
    }

    /**
     *
     */
    public function __set($key, $value)
    {
        try{
            if ($this->isFillable($key)) {
                return $this->setAttribute($key, $value);
            }
            throw new Exception("{$key} is not a valid property");

        } catch(Exception $e) {
//            Log::log($e->getMessage());
        }

    }

    public function __isset($name)
    {
        return $this->isAttributeEmpty($name);
    }

    public function __call($method, $args = null)
    {
        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], $args);
        }
    }

    /**
     * Find a single model filtering with the given params
     * @param array $params
     * @return static|bool
     */
    public static function find($params = [])
    {
        $model = new static();

        $filters = [];

        foreach ($params as $key => $value) {
            if(
                array_key_exists($key, $model->attributes) ||
                $key === 'id'
            ){
                $filters[$key] = $value;
            }
        }

        $attributes = DB()->get(
            $model->table,
            '*',
            $filters
            );

        if($attributes){
            $model = new $model($attributes);
            $model->exists = true;
            return $model;
        }

        return false;
    }

    /**
     * Find all of the models given a params list
     * @param array $params
     * @param bool $count
     * @param array $pagination
     * @return static|bool
     */
    public static function findAll($params = [], $count = false, $pagination = [])
    {
        $model = new static();

        $filters = [];
        foreach ($params as $key => $value) {
            if(
                array_key_exists($key, $model->attributes) ||
                $key === 'id'
            ){
                $filters[$key] = $value;
            }
        }

        //Apply pagination
        if (isset($pagination['res_per_page'])) {
            //todo pagination
        }

        if ($count === true) {
            return DB()->count($model->table, $filters);
        }

        $results = DB()->select(
            $model->table,
            '*',
            $filters
            );

        if($results){
            $items = [];
            foreach ($results as $key => $attributes) {
                $items[] = (new $model($attributes))->getAttributes();
                if ($attributes) {
                    $model->exists = true;
                }
            }

            return $items;
        }

        return false;
    }

    /**
     * Attempts to save a model by creating or updating depending if it exists or not
     * @param array $attributes
     * @return mixed
     */
    public function save($attributes = [])
    {
        $this->fill($attributes);

        if (!$this->exists) {
            $result = $this->create();
        } else {
            $result = $this->update();
        }

        return $result;
    }

    /**
     * Filter attributes found only in the schema
     */
    private function filterAttributesFromSchema(): void
    {
        $this->attributes = array_intersect_key(
            $this->attributes,
            array_flip($this->attributesInSchema)
        );
    }
}