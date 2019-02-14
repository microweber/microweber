<?php only_admin_access() ?>





<?php


$author = array_first(explode('/',$item['name']));
if(isset($item['authors']) and isset($item['authors'][0])  and isset($item['authors'][0]['name'])){
    $author = $item['authors'][0]['name'];

}



?>

<div class="mw-ui-box" style="min-height: 300px;">
    <div class="mw-ui-box-header">
        <span class="mw-icon-gear"></span><span> <?php print $item['name'] ?></span>
    </div>


    <div class="mw-ui-box-content">

        <p class="m-b-20"><?php print $item['description'] ?></p>

        <table cellspacing="0" cellpadding="0" class="mw-ui-table" width="100%">
            <tbody>
            <tr>
                <td>Release date</td>
                <td><?php print $item['latest_version']['release_date'] ?></td>

            </tr>
            <tr>
                <td>Version</td>
                <td><?php print $item['latest_version']['version'] ?></td>

            </tr>
            <tr>
                <td>Author</td>
                <td><?php print $author ?></td>
            </tr>
            <tr>
                <td>Website</td>
                <td><a href="#" class="mw-blue">plumtex.com</a></td>
            </tr>
            </tbody>
        </table>


        <pre>
        <?php

        print_r($item);
        ?></pre>

        <div class="text-center m-t-20">
            <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info">Read more</a>
            <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification">Install</a>
            <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-important mw-ui-btn-outline"><i class="mw-icon-trash-a"></i></a>
        </div>
    </div>
</div>




