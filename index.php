<?php

require_once __DIR__ . '/vendor/autoload.php';
use App\ChatbotHelper;

// Create the chatbot helper instance
$chatbotHelper = new ChatbotHelper();

// Facebook webhook verification
$chatbotHelper->verifyWebhook($_REQUEST);

// Get the fb users data
$input = json_decode(file_get_contents('php://input'), true);
$senderId = $chatbotHelper->getSenderId($input);

if ($senderId && $chatbotHelper->isMessage($input)) {

    // Get the user's message
    $message = $chatbotHelper->getMessage($input);

    // Example 1: Get a static message back
    $replyMessage = $chatbotHelper->getAnswer($message);

    // Example 2: Get foreign exchange rates
    // $replyMessage = $chatbotHelper->getAnswer($message, 'rates');

    // Example 3: If you want to use a bot platform like Dialogflow
    // Don't forget to place your Dialogflow Client access token in the .env file
    // $replyMessage = $chatbotHelper->getAnswer($message, 'dialogflow');

    // Send the answer back to the Facebook chat
    $chatbotHelper->send($senderId, $replyMessage);

}
