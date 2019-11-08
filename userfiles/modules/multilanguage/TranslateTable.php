<?php

class TranslateTable {

    protected $columns = array();
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

                $findTranslation = $this->findTranslate($saveTranslation);
                if ($findTranslation) {
                    $saveTranslation['id'] = $findTranslation['id'];
                }

                $saveTranslation['allow_html'] = 1;
                $saveTranslation['allow_scripts'] = 1;

                db_save('translations', $saveTranslation);
            }
        }
    }

    public function findTranslate($filter) {

        if (!isset($filter['locale']) || empty($filter['locale'])) {
            $filter['locale'] = $this->getCurrentLocale();
        }

        $filter['single'] = 1;

        unset($filter['field_value']);

        return db_get('translations', $filter);
    }

    public function getTranslate($data) {

        foreach ($this->columns as $column) {

            $filter = array();
            $filter['single'] = 1;
            $filter['locale'] = $this->getCurrentLocale();
            $filter['rel_type'] = $this->relType;
            $filter['rel_id'] = $data[$this->relId];
            $filter['field_name'] = $column;

            $translate = db_get('translations', $filter);

            if (!empty($translate['field_value'])) {
                $data[$column] = $translate['field_value'];
            }

        }

        return $data;
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