<?php

/*
|--------------------------------------------------------------------------
| Application config
|--------------------------------------------------------------------------
|
| Define you config values here.
|
*/

return [
    'webhook_verify_token' => getenv('WEBHOOK_VERIFY_TOKEN'),
    'access_token'         => getenv('PAGE_ACCESS_TOKEN'),
    'dialogflow_token'     => getenv('DIALOGFLOW_TOKEN'),
    'witai_token'          => getenv('WITAI_TOKEN'),
];