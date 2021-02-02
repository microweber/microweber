<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/2/2021
 * Time: 2:51 PM
 */

namespace MicroweberPackages\Translation\Http\Controllers;

use Illuminate\Http\Request;
use MicroweberPackages\Translation\Models\Translation;

class TranslationController {

    public function save(Request $request) {

       $translations = $request->post('translations');

       foreach ($translations as $translationLocales) {
           foreach ($translationLocales as $translationLocale=>$translation) {

               $findTranslataion = Translation::where('key',$translation['key'])
                   ->where('locale', $translationLocale)
                   ->where('group', $translation['group'])
                   ->where('namespace', $translation['namespace'])
                   ->first();

               if ($findTranslataion == null) {
                   $findTranslataion = new Translation();
                   $findTranslataion->locale = $translationLocale;
                   $findTranslataion->key = $translation['key'];
                   $findTranslataion->namespace = $translation['namespace'];
                   $findTranslataion->group = $translation['group'];
               }

               $findTranslataion->text = trim($translation['text']);
               $findTranslataion->save();
           }
       }

    }

}