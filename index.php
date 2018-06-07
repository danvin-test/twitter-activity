<?php
date_default_timezone_set('UTC');
require_once  __DIR__  .  '/vendor/autoload.php'; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
 
$app  =  new  Silex\Application();
$app['debug'] = false;


$app->get('/',  function()  use($app)  {     
    return  'Try /hello/:name';
});

$app->get('/hello/{name}',  function($name)  use($app)  {     
    return  'Hello '  .  $app->escape($name);
});

$app->get('/histogram/{username}',  function($username)  use($app)  {      
    $configJSON = json_decode(file_get_contents(__DIR__ . '/config.json'), true);
    $tweets = [];
    $twitterResponse = [];
    $maxId = 0;
    $twitter = new TwitterAPIExchange($configJSON['twitterApiSettings']);
    $twitterQueryString = $configJSON['twitterRequestSettings']['queryString'];
    $i=0;


    while (true) {
        $i++;
        if ($maxId != 0){
            --$maxId;
            $twitterQueryString = $configJSON['twitterRequestSettings']['queryString'] . '&max_id='.$maxId;
        }
        $twitterResponse = json_decode($twitter->setGetfield(str_replace('{screen_name}',$app->escape($username),$twitterQueryString))
                 ->buildOauth($configJSON['twitterRequestSettings']['apiUrl'], $configJSON['twitterRequestSettings']['requestMethod'])
                 ->performRequest());
        if ((count($twitterResponse->errors) > 0) || isset($twitterResponse->error)){
            $errMsg = (isset($twitterResponse->error)) ? $twitterResponse->error : '[Error code: '.$twitterResponse->errors[0]->code.'] '.$twitterResponse->errors[0]->message.' See: https://developer.twitter.com/en/docs/basics/response-codes.html ';
            return new Response($errMsg, 500);
        }

        if (sizeof($twitterResponse)==0) {
            break;
        }
        
        foreach($twitterResponse as $tweet){
            $tweetHour = date('H', strtotime($tweet->created_at));
            isset($tweets[$tweetHour]) ? $tweets[$tweetHour]++ : $tweets[$tweetHour] = 1;
        }
        $maxId = $twitterResponse[count($twitterResponse)-1]->id;
    }
    arsort($tweets);

    return new Response(
        json_encode($tweets),
        200,
        ['Content-Type' => 'application/json']
    );
});




$isCLI = ( php_sapi_name() == 'cli' );
if ($isCLI){
    list($_, $method, $path) = $argv;
    if (!empty($method) && !empty($path)){
        $request = Request::create($path, $method);
        $app->run($request);
    }
}else{
    $app->run();
}
    
?>
