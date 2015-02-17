<?php

api_expose('mw_payments_settings_save_key', function($params){
   return app()->mw_payments->settings_save_key($params);
 });
