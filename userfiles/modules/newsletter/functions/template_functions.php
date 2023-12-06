<?php

/*
 * EMAIL TEMPLATES FUNCTIONS
 */

api_expose_admin('newsletter_get_template');
function newsletter_get_template($data) {
    $data = ['id' => $data['id'], 'single' => true];

    $findNewsletterTemplate = \MicroweberPackages\Modules\Newsletter\Models\NewsletterTemplate::where('id', $data['id'])->first();
    if ($findNewsletterTemplate) {
        return $findNewsletterTemplate->toArray();
    }

}

api_expose('newsletter_save_template');
function newsletter_save_template($data) {
    $table = 'newsletter_templates';
    $data['allow_html'] = true;
    return db_save($table, $data);
}

api_expose('newsletter_delete_template');
function newsletter_delete_template($params) {
    if (isset($params['id'])) {
        $table = 'newsletter_templates';
        $id = $params['id'];
        return db_delete($table, $id);
    }
}

api_expose('newsletter_get_templates');
function newsletter_get_templates() {

    $getTemplates = \MicroweberPackages\Modules\Newsletter\Models\NewsletterTemplate::query();
    $getTemplates = $getTemplates->orderBy('id', 'desc');
    $getTemplates = $getTemplates->get();

    if ($getTemplates) {
        return $getTemplates->toArray();
    }

}
