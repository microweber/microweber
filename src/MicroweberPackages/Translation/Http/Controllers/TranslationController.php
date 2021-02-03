<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/2/2021
 * Time: 2:51 PM
 */

namespace MicroweberPackages\Translation\Http\Controllers;

use function _HumbugBox58fd4d9e2a25\pcov\clear;
use Illuminate\Http\Request;
use MicroweberPackages\Translation\Models\Translation;

class TranslationController {

    public function save(Request $request) {

       $translations = $request->post('translations');

       foreach ($translations as $translationLocales) {
           foreach ($translationLocales as $translationLocale=>$translation) {

               $findTranslataion = Translation::
                   whereRaw("md5(translation_key) = ?", [md5($translation['translation_key'])])
                   ->where('translation_locale', $translationLocale)
                   ->where('translation_group', $translation['translation_group'])
                   ->where('translation_namespace', $translation['translation_namespace'])
                   ->first();

               if ($findTranslataion == null) {
                   $findTranslataion = new Translation();
                   $findTranslataion->translation_locale = $translationLocale;
                   $findTranslataion->translation_key = $translation['translation_key'];
                   $findTranslataion->translation_namespace = $translation['translation_namespace'];
                   $findTranslataion->translation_group = $translation['translation_group'];
               }

               $findTranslataion->translation_text = trim($translation['translation_text']);
               $findTranslataion->save();

               clearcache();
           }
       }

    }

}