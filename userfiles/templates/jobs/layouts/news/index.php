<?php

/*

type: layout

name: layout

description: site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>

<? if(!empty($post)) : ?>
<? include TEMPLATE_DIR. "layouts/news/".'inner.php'; ?>
<? else : ?>
<? include TEMPLATE_DIR. "layouts/news/list.php"; ?>
<? endif; ?>
<? include TEMPLATE_DIR. "footer.php"; ?>
