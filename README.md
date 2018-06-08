Simple PHP Twitter User Activity Checker (based on Silex 2.3, Twitter API v1.1)
================

This small web app takes user's twitter screen name as a parameter and return a JSON-encoded array containing "hour -> tweets  count" for a given user, to determine what hour of the day they are most active. 
It also provides demonstration of the Silex's routing.

Requirements
----
1. PHP 7.1.3 or later
2. Enabled PHP CURL module
3. Enabled mod_rewrite module (only for usage on webserver)

Installation
----
1. Install Composer

        curl -s https://getcomposer.org/installer | php

2. Execute    

        php composer.phar create-project danvin-test/twitter-activity project_name

Configuration
------
Edit **config.json**, located in the root folder of the project, to set up your [Twitter API credentials](https://developer.twitter.com/en/docs/basics/getting-started).

**Notable mention**
1. This web app uses [statuses/user_timeline](https://developer.twitter.com/en/docs/tweets/timelines/api-reference/get-statuses-user_timeline.html) Twitter API. Folow the link to read more about resource information.
2. Advanced configuration available in the config.json, under "twitterRequestSettings" section.

Usage
----

The application has 3 endpoints:
1. "/" - will respond with "Try  /hello/:name" as text
2. "/hello/Daniel" - will respond with “Hello Daniel” as text
3. "/histogram/Ferrari" - will respond with a JSON-encoded array displaying the number of tweets per hour of the day. Most active hours will be presented at the top of the array.


**CLI**

    php index.php GET /                            
    php index.php GET /hello/Daniel
    php index.php GET /histogram/Ferrari


**WebServer**

    http://localhost/
    http://localhost/hello/daniel
    http://localhost/histogram/Ferrari


Running the tests
----
1. Make sure you have PHPUnit [installed](https://phpunit.de/getting-started/phpunit-7.html).
2. To execute the tests, navigate into your project's folder and run

        phpunit


Built With
-----
1. [Silex](https://silex.symfony.com/)
2. [twitter-api-php](https://github.com/J7mbo/twitter-api-php)
