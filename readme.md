# Chatbot PHP Boilerplate


This package makes it simple to start building a chatbot in PHP. Give me 10 minutes of your time and I will give you a chatbot starter setup.

[![Software License](https://img.shields.io/badge/License-MIT-brightgreen.svg)](https://github.com/christophrumpel/chatbot-php-boilerplate/blob/master/LICENSE.txt)
![GitHub version](https://img.shields.io/badge/version-v0.1-brightgreen.svg)

---

If you want to start building a chatbot in PHP, then this boilerplate is a perfect start. It includes everything you need
 to know to connect you application to a messenger platform. You will find simple examples to 
 reply to chat messages. (Currently only Facebook Messenger is supported)
 
Additionally this boilerplate supports bot platforms like [api.ai](http://api.ai) and [wit.ai](http://wit.ai) too. This will help you to process and understand the user's intent.

This package uses [PSR-1][] and [PSR-2][],  If you notice compliance oversights, please send a patch via pull request.

## Requirements

* >= PHP 7
* Composer

## Supported messenger platform

* Facebook Messenger
* more coming

## Covered

* Create a FB Messenger app
* Create a FB Page
* Setup the Chatbot PHP Boilerplate
* Create a webhook
* Connect the Facebook app to the Facebook page

## Not covered

* How to use api.ai
* How to use wit.ai

## Installation

### Create a FB page

First login to Facebook and [create a Facebook page](https://www.facebook.com/pages/create). The page doesn't need to be 
public. Choose the settings that fits best your bot, but for testing it is not important.

### Create a FB Messenger app

Go to the [developer's app page](https://developers.facebook.com/apps/). Click "Add a New App" and
 fill the basic app fields.

![Image of Facebook app creation](http://screenshots.nomoreencore.com/chatbot_create_fb_app.png)

On the "Product Setup" page choose Messenger and click "Get Started".

![Image of the app product setup](http://screenshots.nomoreencore.com/chatbot_create_fb_app_setup.png)

Now we need to create a token to give our app access to our Facebook page. Select the created page, grant permissions and copy the generated token. We need that one later.

![Image of the token creation](http://screenshots.nomoreencore.com/chatbot_fb_app_create_page_token.png)

### Setup the Chatbot PHP Boilerplate

First clone the repository and remove the existing git folder.
``` bash
git clone git@github.com:christophrumpel/chatbot-php-boilerplate.git chatbot-boilerplate
```

``` bash
cd chatbot-boilerplate
rm -rf .git
```

Now we need to install the Composer dependencies:

``` bash
composer install
```

This boilerplate is working with an `.env` file (environment). All sensible data like keys are stored there. This file 
should be listed in your `.gitignore` file. This is because this data should not be included in your repository. 
Additionally you are able to use different keys on different environments. (e.g. test bot platform account on your local
 environment)
 
In this boilerplate there is an example file included called `.env.example`. Rename it in order to use it.

``` bash
mv .env.example .env
```

Next take a look at this file. Here we have two values to consider for now. First one is the `WEBHOOK_VERIFY_TOKEN` which is a token you can define yourself here. Fill something in now, we will need it later. The second value ist the `PAGE_ACCESS_TOKEN` which we already got from our messenger app. Fill it in here. Perfect!

## Create a webhook for the messenger app

On our PHP application we need to have a webhook. This means a public URL that Facebook can talk to. Every time the user
 writes a message inside the FB chat, FB will send it to this URL which is the entrance point to our PHP application. In this boilerplate it is the index.php file.

So we need a public URL to the index.php file and there are two options here for you.

### Make it live

If you got a server you can push your code there where you have public access to it. The URL then maybe looks like `https://yourserver.com/chatbot-php-boilerplate/`.

### Do it locally

For testing it is definitely easier when you don't have to push every change to a live server in order to test the code. 
This is why I use a local public URL. There are multiple services out there that generate a public URL to your local server. Checkout out [ngrok](https://www.sitepoint.com/use-ngrok-test-local-site/) or use [Laravel Valet Sharing](https://laravel.com/docs/5.2/valet#sharing-sites) which is my choice since I'm using Valet already. (Laravel Valet is using ngrok under the hood too)

It doesn't matter how you do it, but you just need a public secured URL to the `index.php` file. (https!). This is my URL: `https://7def2gH4.ngrok.io`

### Connect the Facebook app to your application

Now that we got the URL we need to setup the webhook. Go back to you Facebook app settings and click `Setup Webhooks` 
inside the Webhooks part.

![Image of Facebook app webhook setup](http://screenshots.nomoreencore.com/chatbot_fb_app_setup_webhook.png)

Fill in in the public URL, the `WEBHOOK_VERIFY_TOKEN` from the `.env` file, check all the subscription fields and click 
`Verify and Save`.

![Image of Facebook app webhook infos](http://screenshots.nomoreencore.com/chatbot_fb_app_setup_webhook_info.png)

If you did everything right you have a working webhook now. If not you will see an error icon at the webhook URL field. This happens if the URL or the token is not correct.

### Connect the Facebook app to the Facebook page

Now the last step of the installation will make sure that our Facebook app is connected to the Facebook page. For this purpose there is a dropdown within your `Webhooks` setting page. Choose you page here and click `Subscribe`. 

![Image of Facebook app webhook select page to subscribe to](http://screenshots.nomoreencore.com/chatbot_webhook_page_selection.png)


### Test it

So finally we can test the whole setup. Go to you Facebook page and click the message button in order to send a message. Type `Hi` and press enter. You should now see this answer: `Define your own logic to reply to this message: Hi`

![Image showing your first chatbot response](http://screenshots.nomoreencore.com/chatbot_response.png)

If you see this, then congratulations. You did it! You have successfully installed the Chatbot PHP Boilerplate and received your first reply.

If you don't get a reply, then something went wrong =( Check your server's log files to find out more. Additionally you can use the built in Monolog Logger to debug the applications.


# Usage

## Example 1: Static message

In your `index.php` file you will find this line of code:

```php
$replyMessage = $chatbotHelper->getAnswer($message);
```

Here the user's message is being used to get an answer. In this case the message is analysed in the `ChatbotAi method getAnswer`. It is simply returning a static text with the original message. Like mentioned below, you can define your own logic to respond to the message. It is also common to use PHP's `preg_match` function to look for words inside the message. In the example the method return some hello text, if the message contains `hi` , `hey` or `hello`.

## Example 2: Foreign Exchange Rates

Here a public API is used to return foreign exchange rates to the user. The user can type currencies like `EUR`, `USD`, 
`CHF` etc. It is a simple example but good to see how to work with external APIs.

![Image showing the rates example](http://screenshots.nomoreencore.com/chatbot_rates_example.png)

## Example 3: Using bot platforms

Bot platforms can help you analyse the user's intent of a message. Currently only [api.ai](https://api.ai/) and ([wit
.ai](https://wit.ai/) are supported.

### API.ai
To use api.ai you just need to add the parameter `apiai` to the `getAnswer` method. There is also an example in your `index.php` 
file.

``` php
// Example 3: If you want to use a bot platform like api.ai try
// Don't forget to provide your api.ai token in the .env file
$replyMessage = $chatbotHelper->getAnswer($message, 'apiai');
```

### Wit.ai
To use wit.ai you just need to add the parameter `witai` to the `getAnswer` method. There is also an example in your 
`index.php` 
file.

``` php
// Example 4: If you want to use a bot platform like wit.ai
// Don't forget to place your Wit.ai Client access token in the .env file (WITAI_TOKEN)
$replyMessage = $chatbotHelper->getAnswer($message, 'witai');
```

Wit.ai will analyze the users's message. This example implementation will just send back the user's intent.

![Image showing the response with wit.ai](http://screenshots.nomoreencore.com/chatbot_witai_response.png)

Of course you need to add a story to you Wit.ai application like:

![Image showing the story of wit.ai](http://screenshots.nomoreencore.com/chatbot_witai_story.png)

## License

The MIT License (MIT).


[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
