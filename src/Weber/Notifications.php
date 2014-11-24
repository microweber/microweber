<?php




use Illuminate\Database\Eloquent\Model as Eloquent;

class Notifications extends Eloquent
{

    public $table = 'notifications';

    public function __construct()
    {
        $this->init_db();
    }


    public static function boot()
    {

        parent::boot();

    }

    public function rel()
    {
        return $this->morphTo();
    }


    public function init_db()
    {
        static $is_init;
        if (!$is_init) {
            $is_init = true;
            $fields_to_add = array();
            $fields_to_add['updated_on'] = 'dateTime';
            $fields_to_add['created_on'] = 'dateTime';
            $fields_to_add['created_by'] = 'integer';
            $fields_to_add['edited_by'] = 'integer';
            $fields_to_add['rel_id'] = 'integer';
            $fields_to_add['rel_type'] = 'string';
            $fields_to_add['notif_count'] = 'integer';
            $fields_to_add['is_read'] = 'integer';
            $fields_to_add['title'] = 'longText';
            $fields_to_add['description'] = 'longText';
            $fields_to_add['content'] = 'longText';
            $fields_to_add['installed_on'] = 'dateTime';

            app()->database->build_table($this->table, $fields_to_add);
            return $fields_to_add;
        }
    }




}

