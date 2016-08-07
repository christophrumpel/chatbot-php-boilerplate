<?php

require_once __DIR__ . '/vendor/autoload.php';
use App\ChatbotHelper;
use App\ForeignExchangeRate;

// Load config data
$config = include('config.php');

$accessToken = $config['access_token'];
$verifyToken = $config['verify_token'];
$hubVerifyToken = null;

if (isset($_REQUEST['hub_challenge'])) {
    $challenge = $_REQUEST['hub_challenge'];
    $hubVerifyToken = $_REQUEST['hub_verify_token'];
}

if ($hubVerifyToken === $verifyToken) {
    echo $challenge;
}

$input = json_decode(file_get_contents('php://input'), true);

// Create a chatbot helper instance
$chatbotHelper = new ChatbotHelper($accessToken, $config);

// Get the fb users id
$senderId = $chatbotHelper->getSenderId($input);

if ($senderId) {

    // Get the user's message
    $message = $chatbotHelper->getMessage($input);

    // Example 1: Get a static message back
    $replyMessage = $chatbotHelper->getAnswer($message);

    // Example 2: Get foreign exchange rates
    //$replyMessage = $chatbotHelper->getAnswer($message, 'rates');

    // Example 3: If you want to use a bot platform like api.ai try
    // $replyMessage = $chatbotHelper->getAnswer($message, 'apiai');

    // Send the answer back to the Facebook chat
    $chatbotHelper->send($senderId, $replyMessage);

}
