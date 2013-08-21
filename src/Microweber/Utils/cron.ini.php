; <?php exit(); __halt_compiler(); ?> 
[make_full_backup]
name = "make_full_backup"
interval = "1 sec"
callback[0] = "\Microweber\Utils\Backup"
callback[1] = "cronjob"
params[type] = "full"
last_run = 1375715240

 