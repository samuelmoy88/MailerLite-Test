<?php

use Core\App;
use Core\Router;

//Boot up the app
$app = new App();

//Get the request object from the container
/** @var Core\Request $request */
$request = $app->container->get('Request');

//Check if the request is valid
if (!$request->isValid()) {

    $app->sendResponse(
        \Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST,
        ['message' => 'Invalid request']
    );
}
//Valid response, turn off errors from now on
error_reporting(0);

//Dispatch the route and get the response
[$code, $response, $headers] = Router::dispatch($app);

//Send the response back to the user
$app->sendResponse(
    $code,
    $response,
    $headers
);
