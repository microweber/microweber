<?php



class Content extends \Microweber\Content
{

    function get($params = null)
    {

        $modified = array();
        $original = parent::get($params);
        if (!empty($original)) {
            foreach ($original as $item) {
                $item['title'] = 'Hey i just override the content class';
                $modified[] = $item;
            }
            return $modified;
        }
        return $original;
    }


}