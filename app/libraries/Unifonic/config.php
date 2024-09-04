<?php
require_once(app_path() . '/Helpers/Custom.php');

$custom = new \App\Helpers\Custom();
$api = $custom::api_settings();
return array(
    //'AppSid' => 'iKGqCUZdax_viXKziUG1ieMvHXQMlB',
    'AppSid' => $api->unifonic_app_id,
    'ApiURL' => $api->unifonic_app_url

);
