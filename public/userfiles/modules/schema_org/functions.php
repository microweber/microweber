<?php


event_bind('mw.front', function ($params = false) {
    template_foot(function () {

        $contentId = content_id();
        if ($contentId) {
            $graph = new \Spatie\SchemaOrg\Graph();
            $graphFilled = getSchemaOrgContentFilled($graph, $contentId);

            if ($graphFilled) {
                return $graphFilled->toScript();
            }
        }

    });
});
