<?php

require_once (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'parser' . DIRECTORY_SEPARATOR . 'PQLite' . DIRECTORY_SEPARATOR . 'PQLite.php');


$pq = new PQLite($layout);


$els = $pq->find('body div.edit')->each('parse_elem_callback');

$layout = $pq->getHTML();
$pq = null;
unset($pq);