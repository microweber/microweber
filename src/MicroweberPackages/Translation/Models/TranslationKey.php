<?php

namespace MicroweberPackages\Translation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use MicroweberPackages\Core\Models\HasSearchableTrait;

class TranslationKey extends Model
{
    use HasSearchableTrait;

    public $timestamps = false;
    public $table = 'translation_keys';

    protected $searchable = [
        'id',
        'translation_keys',
        'translation_texts',
    ];

    public function texts()
    {
        return $this->hasMany(TranslationText::class);
    }

    public static function getNamespaces()
    {
        $queryModel = static::query();
        $queryModel->groupBy('translation_namespace');
        $namespaces =  $queryModel->get()->toArray();

        $readyNamespaces = [];

        foreach($namespaces as $namespace) {

            $translationNamespace = $namespace['translation_namespace'];

            if ($namespace['translation_namespace'] == '') {
                $translationNamespace = 'global';
            }

            if ($namespace['translation_namespace'] == '*') {
                $translationNamespace ='global';
            }

            $readyNamespaces[$translationNamespace] = $namespace;
        }

        return $readyNamespaces;
    }

    public static function getGroupedTranslations($filter = [])
    {
        if (!isset($filter['page'])) {
            $filter['page'] = 1;
        }

        $engine = mw()->database_manager->get_sql_engine();
        $like = 'like';
        if ($engine == 'mysql') {
            $like = 'ilike';
        }

        if (!isset($filter['translation_namespace'])) {
            $filter['translation_namespace'] = '*';
        }

        $queryModel = static::query();
        $queryModel->groupBy("translation_key");
        $queryModel->where("translation_namespace", $filter['translation_namespace']);

        if (isset($filter['search']) && !empty($filter['search'])) {

			$queryModel->where(function($subQuery) use ($filter,$like) {

				$subQuery->where('translation_key', $like, '%' . $filter['search'] . '%');
				$subQuery->where('translation_namespace', $filter['translation_namespace']);
			});

            $queryModel->orWhereHas('texts', function($subQuery) use ($filter,$like) {
                $subQuery->where('translation_text', $like, '%' . $filter['search'] . '%');
				$subQuery->where('translation_namespace', $filter['translation_namespace']);
            });

        }

        $queryModel->orderBy('id', 'asc');

         Paginator::currentPageResolver(function() use ($filter) {
            return $filter['page'];
        });

        $getTranslationsKeys = $queryModel->paginate(50);
        $pagination = $getTranslationsKeys->links("pagination::bootstrap-4-flex");

        $group = [];

        foreach ($getTranslationsKeys as $translationKey) {

            $translationLocales = [];
            $getTranslationTextLocales = TranslationText::
                where('translation_key_id', $translationKey->id)
                ->get()
                ->toArray();

            foreach ($getTranslationTextLocales as $translationTextLocale) {
                $translationLocales[$translationTextLocale['translation_locale']] = $translationTextLocale['translation_text'];
            }

            $group[$translationKey->translation_key] = $translationLocales;
        }

        return ['results'=>$group,'pagination'=>$pagination];

    }
}
