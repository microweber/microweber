<?php

class TranslateTable {

    protected $primaryId = false;
    protected $recognitionIds = array();
    protected $table = false;
    protected $columns = array();

    public function saveOrUpdate($data) {

        if (empty($this->columns) || empty($this->recognitionIds)) {
            return;
        }

        $saveTranslations = array();
        $saveTranslations['locale'] = $this->getCurrentLocale();
        
        foreach ($this->columns as $column) {
            if (isset($data[$column])) {
                $saveTranslations[$column] = $data[$column];
            }
        }

        foreach ($this->recognitionIds as $primaryId=>$recognitionId) {
            $saveTranslations[$recognitionId] = $data[$primaryId];
        }

        $findTranslations = $this->getTranslate($data);
        if ($findTranslations) {
            $saveTranslations['id'] = $findTranslations['id'];
        }

        db_save($this->_getTable(), $saveTranslations);
    }

    public function getTranslate($data, $locale = false) {

        if (!$locale) {
            $locale = $this->getCurrentLocale();
        }

        $filter = array();
        $filter['locale'] = $locale;
        $filter['single'] = 1;

        foreach ($this->recognitionIds as $primaryId=>$recognitionId) {
           $filter[$recognitionId] = $data[$primaryId];
        }

        return db_get($this->_getTable(), $filter);
    }

    public function getCurrentLocale()
    {
        $locale = get_option('language', 'website');
        if (empty($locale)) {
            $locale = 'en';
        }

        return $locale;
    }

    private function  _getTable() {
        return $this->table . '_translations';
    }
}