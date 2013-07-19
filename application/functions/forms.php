<?php




function countries_list()
{

    return \mw\Forms::countries_list();

}

api_expose('save_form_list');
function save_form_list($params)
{
    return \mw\Forms:: save_list($params);

}


api_expose('delete_forms_list');

function delete_forms_list($data)
{
    return \mw\Forms:: delete_list($data);
}

api_expose('delete_form_entry');

function delete_form_entry($data)
{
    return \mw\Forms:: delete_entry($data);

}

api_expose('forms_list_export_to_excel');
function forms_list_export_to_excel($params)
{


    return \mw\Forms:: export_to_excel($params);


}


function get_form_entires($params)
{
    return \mw\Forms:: get_entires($params);

}

function get_form_lists($params)
{
    return \mw\Forms:: get_lists($params);
}

api_expose('post_form');
function post_form($params)
{
    return \mw\Forms:: post($params);


}
