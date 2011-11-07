<?php ## Главный конфигурационный файл сайта.
// Подключается ко всем сценариям (автоматически или вручную)
if (!defined("PATH_SEPARATOR"))
  define("PATH_SEPARATOR", getenv("COMSPEC")? ";" : ":");
ini_set("include_path", ini_get("include_path").PATH_SEPARATOR.dirname(__FILE__));
?>