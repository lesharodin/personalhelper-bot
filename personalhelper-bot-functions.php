<?php

$access_token = '<152179862:AAH5HvR_hymAZElzLJ8ZNNdRqrlMBoSReMo>';
$api = 'https://api.telegram.org/bot' . $access_token;

$output = json_decode(file_get_contents('php://input'), TRUE);
$chat_id = $output['message']['chat']['id'];
$message = $output['message']['text'];

switch($message) {
  
  case '/test':
    
    
    $text = 'hello world';
    
    sendMessage($chat_id, $text);
    break;
  default:
    break;
}

function sendMessage($chat_id, $message) {
  file_get_contents($GLOBALS['api'] . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message));
}
