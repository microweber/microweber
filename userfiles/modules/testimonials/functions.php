<?php

api_expose('save_testimonial');
function save_testimonial($data)
{
    if (!is_admin()) {
        return;
    }
    $table = "testimonials";
    return save($table, $data);
}



function get_testimonials($params=array())
{
    if (is_string($params)) {
        $params = parse_params($params);
    }
    $params['table'] = "testimonials";
	$params['order_by'] = "position asc";
    return get($params);
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
        return delete_by_id($table, $id);
    }
}

api_expose('reorder_testimonials');
function reorder_testimonials($params)
{
    if (!is_admin()) {
        return;
    }
	if(isset($params['ids'])){
		$table = "testimonials";
    	return mw()->database_manager->update_position_field($table, $params['ids']);
	}
    
}