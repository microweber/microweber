<?php

/*

type: layout
content_type: static
name: Contact Us - Simple

description: Contact us layout
position: 7
*/


?>
<?php include template_dir() . "header.php"; ?>

    <div id="contacts-<?php print CONTENT_ID; ?>">
        <div class="edit" rel="content" field="dream_content">
            <module type="layouts" template="skin-41"/>
            <module type="layouts" template="skin-50"/>
            <module type="layouts" template="skin-47"/>
        </div>
    </div>

<?php include template_dir() . "footer.php"; ?>