<?php

//event_bind('mw.reset_modules_settings', function ($modules_ids)  {
//    if($modules_ids and is_array($modules_ids) and !empty($modules_ids)) {
//        foreach ($modules_ids as  $modules_id) {
//          //  dd($modules_id);
//        }
//    }
//});



api_expose('project_testimonial_autocomplete');
function project_testimonial_autocomplete($params)
{
    if (!is_admin()) {
        return;
    }
    $keyword = false;
    if(isset($params['keyword']) and $params['keyword']){
        $keyword =htmlentities( $params['keyword'], ENT_QUOTES, 'UTF-8', false);
    }


    $getTestimonials = \Illuminate\Support\Facades\DB::table('testimonials');
    if ($keyword) {
        $getTestimonials = $getTestimonials->where('project_name', 'LIKE', '%' . $keyword . '%');
    }
    $getTestimonials=$getTestimonials->groupBy('project_name')->get();


    $testimonials = [];
    if ($keyword) {
        $testimonials[] = [
            'id'=>$keyword,
            'title'=>$keyword,
        ];
    }
    $testimonials[] = [
        'id'=>'All projects',
        'title'=>'All projects',
    ];

    foreach ($getTestimonials as $getTestimonial) {
        $testimonials[] = [
            'id' => $getTestimonial->project_name,
            'title' => $getTestimonial->project_name,
        ];
    }

    return ['data'=>$testimonials];
}

api_expose('save_testimonial');
function save_testimonial($data)
{
    if (!is_admin()) {
        return;
    }

    if (isset($data['project_name'])) {
        $data['project_name'] = trim($data['project_name']);
    }

    $table = "testimonials";
    return db_save($table, $data);
}


function get_testimonials($params = array())
{
    if (is_string($params)) {
        $params = parse_params($params);
    }

    $params['table'] = "testimonials";
    $params['order_by'] = "position asc";

    return db_get($params);
}

api_expose('delete_testimonial');
function delete_testimonial($params)
{
    if (!is_admin()) {
        return;
    }
    if (isset($params['id'])) {
        $table = "testimonials";
        $id = $params['id'];
        return db_delete($table, $id);
    }
}

api_expose('reorder_testimonials');
function reorder_testimonials($params)
{
    if (!is_admin()) {
        return;
    }
    if (isset($params['ids'])) {
        $table = "testimonials";
        return mw()->database_manager->update_position_field($table, $params['ids']);
    }

}
