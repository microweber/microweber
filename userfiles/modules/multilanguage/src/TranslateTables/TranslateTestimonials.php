<?php

class TranslateTestimonials extends TranslateTable {

    protected $relId = 'id';
    protected $relType = 'testimonials';

    protected $columns = [
        'name',
        'content',
        'project_name',
        'client_company',
        'client_role'
    ];

}