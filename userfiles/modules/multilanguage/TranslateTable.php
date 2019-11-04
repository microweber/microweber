<?php

class TranslateTable {

    protected $relId = false;
    protected $relType = false;

    public function saveOrUpdate($data) {

        foreach ($this->columns as $column) {
            if (isset($data[$column])) {

                $saveTranslation = array();
                $saveTranslation['locale'] = $this->getCurrentLocale();
                $saveTranslation['rel_id'] = $data[$this->relId];
                $saveTranslation['rel_type'] = $this->relType;
                $saveTranslation['field_name'] = $column;
                $saveTranslation['field_value'] = $data[$column];

                $findTranslation = $this->getTranslate($saveTranslation);
                if ($findTranslation) {
                    $saveTranslation['id'] = $findTranslation['id'];
                }

                db_save('translations', $saveTranslation);
            }
        }
    }

    public function getTranslate($filter) {

        if (!isset($filter['locale']) || empty($filter['locale'])) {
            $filter['locale'] = $this->getCurrentLocale();
        }

        $filter['single'] = 1;

        unset($filter['field_value']);

        return db_get('translations', $filter);
    }

    public function getCurrentLocale()
    {
        $locale = get_option('language', 'website');
        if (empty($locale)) {
            $locale = 'en';
        }

        return $locale;
    }
}