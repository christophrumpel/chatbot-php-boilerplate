# Chatbot PHP Boilerplate


This package makes it simple to start building a chatbot in PHP. Give me 10 minutes of your time and I will give you a chatbot starter setup.

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/christophrumpel/chatbot-php-boilerplate/blob/master/LICENSE.txt)

---

If you want to start building a chatbot in PHP, then this boilerplate is a perfect start. It includes everything you need
 to know to
 connect you application to a messenger (currently on Facebook Messenger support). You will find simple examples to 
 reply to the messenger.
 
Additionally this boilerplate supports bot platforms like [api.ai](http://api.ai) and [wit.ai](http://wit.ai) (wit
.ai coming soon) too. This will help you to process and understand the user's message and intent.

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

Now we need to create a token to let our app 
access our Facebook page. Select the created page, grant permissions and copy the generated token. We need that one later.

![Image of the token creation](chatbot_fb_app_create_page_token.png)

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
$ composer install
```

Next take a look at the `config.php` file. Here we have two values to consider for now. First is the `verify_token` which is a token you can define yourself here. Change it to something else, we will need it later. The second value ist the `access_token` which we already got from our messenger app. Fill it in here. Perfect!

## Create a webhook for the messenger app

On our PHP application we need to have a webhook. This means a public URL that Facebook can talk to. Every time the user
 writes a message inside the FB chat, FB will send to this URL which is the entrance to our PHP application. In this boilerplate, this is the index.php file.

So we need a public URL to the index.php file and there are two options here for you.

### Make it live

If you got a server you can push your code there where you have public access to it. The URL then maybe looks like `http://yourserver.com/chatbot-php-boilerplate/`.

### Do it locally

For testing it is definitely easier when you don't have to push every change to test the code. This is why I use a local public URL. There are multiple services out there that generate a public URL to your local server. Checkout out [ngrok](https://www.sitepoint.com/use-ngrok-test-local-site/) or use [Laravel Valet Sharing](https://laravel.com/docs/5.2/valet#sharing-sites) which is my choice since I'm using Valet already. (Laravel Valet is using ngrok under the hood too)

It doesn't matter how you do it, but you just need a public secured URL to the `index.php` file. (https!). This is my URL: `https://7def2gH4.ngrok.io`

### Connect the Facebook app to your application

Now that we got the URL we need to setup the webhook. Go back to you Facebook app settings and click `Setup Webhooks` 
inside the Webhooks part.

![Image of Facebook app webhook setup](http://screenshots.nomoreencore.com/chatbot_fb_app_setup_webhook.png)

Fill in in the public URL, the `verify_token` from the `config.php` file, check all the subscription fields and click `Verify and Save`.

![Image of Facebook app webhook infos](http://screenshots.nomoreencore.com/chatbot_fb_app_setup_webhook_info.png)

If you did everything right you have a working webhook now. If not you will see an error icon at the webhook URL field. This happens if the URL or the token is not correct.

### Connect the Facebook app to the Facebook page

Now the last step of the installation will make sure that our Facebook app is connected to the Facebook page. For this purpose there is a select dropdown within your `Webhooks` setting page. Choose you page here and click `Subscribe`. 

![Image of Facebook app webhook select page to subscribe to](http://screenshots.nomoreencore.com/chatbot_webhook_page_selection.png)


### Test it

So finally we can test the whole setup. Go to you Facebook page and click the message button in order to send a message. Type `Hi` and press enter. You should now see this answer: `Define your own logic to reply to this message: Hi`

![Image showing your first chatbot response](http://screenshots.nomoreencore.com/chatbot_response.png)

If you see this, then congratulations. You did it! You have successfully installed the Chatbot PHP Boilerplate and received your first reply.

If you don't get a reply, then something went wrong =( Check your server's log files to find out more. Additionally you can use the built in Monolog Logger to debug the applications.


# Usage

In your `index.php` file you will find this line of code:

```php
$replyMessage = $chatbotHelper->getAnswer($message);
```

Here the user's message is being used to get an answer. In this case the message is analysed in the `ChatbotAi method getAnswer`. It is simply returning a static text with the original message. Like mentioned below the return, you can define your own logic to respond to the message there. It is also common to use PHP's `preg_match` function to look for words inside the message. In the example it is return the hello text if the message contains `hi` , `hey` or `hello`.

## Using bot platforms

Bot platforms can help you analyse the user's intent of a message. Currently only [api.ai](https://api.ai/) is integrated but there will be more. ([wit.ai](https://wit.ai/) is next)

## Using api.ai

To use api.ai you just need to add a parameter to the `getAnswer` method. There is also an example in your `index.php` file.


``` php
// If you want to use a bot platform like api.ai try
$replyMessage = $chatbotHelper->getAnswer($message, 'apiai');
```


``` php
$provider = new \League\OAuth2\Client\Provider\GenericProvider([
```




## License

The MIT License (MIT).


[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
