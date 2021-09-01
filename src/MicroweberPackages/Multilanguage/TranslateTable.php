<?php
namespace MicroweberPackages\Multilanguage;

class TranslateTable
{

    protected $columns = array();
    protected $relId = false;
    protected $relType = false;
    protected $locale = false;

    protected $repositoryClass = false;
    protected $repositoryMethods = [];

    public function getRelType()
    {
        return $this->relType;
    }

    public function getRelId()
    {
        return $this->relId;
    }

    public function getRepositoryClass()
    {
        return $this->repositoryClass;
    }

    public function getRepositoryMethods()
    {
        return $this->repositoryMethods;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    public function saveOrUpdate($data)
    {

        foreach ($this->columns as $column) {
            if (isset($data[$column]) && !empty($data[$column])) {

                $saveTranslation = array();

                if ($this->locale) {
                    $saveTranslation['locale'] = $this->locale;
                } else {
                    $saveTranslation['locale'] = $this->getCurrentLocale();
                }

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

                db_save('multilanguage_translations', $saveTranslation);
            }
        }
    }

    public function findTranslate($filter)
    {

        if (!isset($filter['locale']) || empty($filter['locale'])) {
            $filter['locale'] = $this->getCurrentLocale();
        }

        $filter['single'] = 1;

        unset($filter['field_value']);

        return db_get('multilanguage_translations', $filter);
    }


    public static $_getTranslateTranslates = [];
    public function getTranslate($data)
    {
        if (!isset($data[$this->relId])) {
            return $data;
        }

        if (!empty(self::$_getTranslateTranslates[$this->relType][$this->getCurrentLocale()])) {
            $translates = self::$_getTranslateTranslates[$this->relType][$this->getCurrentLocale()];
        } else {
            $translates = app()->multilanguage_repository->getTranslationsByRelTypeAndLocale($this->relType, $this->getCurrentLocale());
            self::$_getTranslateTranslates[$this->relType][$this->getCurrentLocale()] = $translates;
        }

        if ($translates) {
            foreach ($translates as $translate_item) {
                if (isset($translate_item['rel_type']) and $translate_item['rel_type'] == $this->relType) {
                    if (isset($translate_item['rel_id']) and $translate_item['rel_id'] == $data[$this->relId]) {
                        foreach ($this->columns as $column) {
                            if (isset($translate_item['field_name']) and $translate_item['field_name'] == $column) {
                                if (!empty($translate_item['field_value'])) {
                                    if (isset($data[$column])) {
                                        $data[$column] = $translate_item['field_value'];
                                    }
                                }
                            }
                        }
                    }
                }
                $data['item_lang'] = $this->getCurrentLocale();
            }
        }

        return $data;
    }

    public static $_getCurrentLocale = false;
    public function getCurrentLocale()
    {
        if (self::$_getCurrentLocale) {
            return self::$_getCurrentLocale;
        }

        self::$_getCurrentLocale = app()->getLocale();

        return self::$_getCurrentLocale;
    }
}
