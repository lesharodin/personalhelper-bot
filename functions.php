<?php
/**
 * Telegram Bot access token and API url.
 */
$access_token = '<ACCESS_TOKEN_HERE>';
$api = 'https://api.telegram.org/bot' . $access_token;

/**
 * Listen commands, set chat ID and message.
 */
$output = json_decode(file_get_contents('php://input'), TRUE);
$chat_id = $output['message']['chat']['id'];
$message = $output['message']['text'];

/**
 * Set response.
 */
switch($message) {
  // Weather API by OpenWeatherMap.
  // @see http://api.openweathermap.org
  case '/pogoda':
    // App ID.
    $appid = '<APP_ID_HERE>';
    // Place (city) ID.
    // @example St.Petersburg, Russia.
    $id = '500776';
    // Weather array.
    $pogoda = json_decode(file_get_contents('http://api.openweathermap.org/data/2.5/weather?appid=' . $appid . '&id=' . $id . '&units=metric&lang=ru'), TRUE);
    // Temperature
    if ($pogoda['main']['temp'] > 0) $temperature = '+' . sprintf("%u", $pogoda['main']['temp']);
    else $temperature = sprintf("%u", $pogoda['main']['temp']);
    // Wind direcrion.
    if ($pogoda['wind']['deg'] >= 0 && $pogoda['wind']['deg'] <= 11.25) $wind_direction = 'северный';
    elseif ($pogoda['wind']['deg'] > 11.25 && $pogoda['wind']['deg'] <= 78.75) $wind_direction = 'северо-восточный, ';
    elseif ($pogoda['wind']['deg'] > 78.75 && $pogoda['wind']['deg'] <= 101.25) $wind_direction = 'восточный, ';
    elseif ($pogoda['wind']['deg'] > 101.25 && $pogoda['wind']['deg'] <= 168.75) $wind_direction = 'юго-восточный, ';
    elseif ($pogoda['wind']['deg'] > 168.75 && $pogoda['wind']['deg'] <= 191.25) $wind_direction = 'южный, ';
    elseif ($pogoda['wind']['deg'] > 191.25 && $pogoda['wind']['deg'] <= 258.75) $wind_direction = 'юго-западный, ';
    elseif ($pogoda['wind']['deg'] > 258.75 && $pogoda['wind']['deg'] <= 281.25) $wind_direction = 'западный, ';
    elseif ($pogoda['wind']['deg'] > 281.25 && $pogoda['wind']['deg'] <= 348.75) $wind_direction = 'северо-западный, ';
    else $wind_direction = '';
    // Set text.
    $text = 'Сейчас ' . $pogoda['weather'][0]['description'] . '. Температура воздуха: ' . $temperature . '°C. Ветер ' . $wind_direction . sprintf("%u", $pogoda['wind']['speed']) . ' м/сек.';
    // Send weather to Telegram user.
    sendMessage($chat_id, $text);
    break;
  default:
    break;
}

/**
 * Function sendMessage().
 */
function sendMessage($chat_id, $message) {
  file_get_contents($GLOBALS['api'] . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message));
}
