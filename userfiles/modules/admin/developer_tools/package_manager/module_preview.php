<?php


use MicroweberPackages\Package\MicroweberComposerClient;

$composerClient = new MicroweberComposerClient();
$composerSearch = $composerClient->search([
    'require_name'=>$params['package_name']
]);

dd($composerSearch);
?>
