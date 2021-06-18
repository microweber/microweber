<?php
namespace MicroweberPackages\App\Parser;


use MicroweberPackages\Content\ContentField;

class ParserContentFields {

    public function recursiveParseContentFields($layout) {

        $docQuery = \phpQuery::newDocument($layout);

        foreach ($docQuery['.edit'] as $editElement) {

            $htmlOnEditElement = '';

            $relType = pq($editElement)->attr('rel');
            $field = pq($editElement)->attr('field');

            $findContentField = ContentField::where('rel_type', $relType)->where('field', $field)->first();
            if ($findContentField !== null) {
                $htmlOnEditElement = $findContentField->value;
            }

            pq($editElement)->html($htmlOnEditElement);
        }

        return $docQuery->htmlOuter();

    }

}
