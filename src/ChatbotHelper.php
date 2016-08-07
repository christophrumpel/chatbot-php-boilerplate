<?php

namespace App;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class ChatbotHelper
{

    protected $chatbotAI;
    protected $facebookSend;
    protected $log;
    private $accessToken;

    public function __construct($accessToken)
    {
        $this->chatbotAI = new ChatbotAI();
        $this->accessToken = $accessToken;
        $this->facebookSend = new FacebookSend();
        $this->log = new Logger('general');
        $this->log->pushHandler(new StreamHandler('debug.log'));
    }

    /**
     * Get the sender id of the message
     * @param $input
     * @return mixed
     */
    public function getSenderId($input)
    {
        return $input['entry'][0]['messaging'][0]['sender']['id'];
    }

    /**
     * @param $input
     * @return mixed
     */
    public function getMessage($input)
    {
        return $input['entry'][0]['messaging'][0]['message']['text'];
    }

    /**
     * Get the answer to a given user's message
     * @param null $api
     * @param string $message
     * @return string
     */
    public function getAnswer($message, $api = null)
    {

        if ($api === 'apiai') {

            return $this->chatbotAI->getApiAIAnswer($message);
        } elseif ($api === 'witai') {

            return $this->chatbotAI->getWitAIAnswer($message);
        } else {

            return $this->chatbotAI->getAnswer($message);
        }

    }

    /**
     * Send a reply back to Facebook chat
     * @param $senderId
     * @param $replyMessage
     */
    public function send($senderId, string $replyMessage)
    {

        $this->log->info('test ' . gettype($senderId) . ' ' . gettype($replyMessage));
        return $this->facebookSend->send($this->accessToken, $senderId, $replyMessage);
    }
}