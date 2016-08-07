<?php

require_once __DIR__ . '/vendor/autoload.php';
use App\ChatbotHelper;

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
$chatbotHelper = new ChatbotHelper($accessToken);

// Get the fb users id
$senderId = $chatbotHelper->getSenderId($input);

if ($senderId) {

    // Get the user's message
    $message = $chatbotHelper->getMessage($input);

    // Lets find a reply to the user's message
    $replyMessage = $chatbotHelper->getAnswer($message);

    // Instead of the simple getAnswer method you can use a bot api too
    $replyMessage = $chatbotHelper->getAnswer($message, 'apiai');

    // Send the answer back to the Facebook chat
    $chatbotHelper->send($senderId, $replyMessage);

}
