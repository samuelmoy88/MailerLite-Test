<?php

/** @var FastRoute\RouteCollector $r */

//Subscriber routes
$r->get( '/subscribers', 'SubscribersController@get');

$r->get( '/subscribers/{subscriber:\d+}', 'SubscribersController@get');

$r->post( '/subscribers', 'SubscribersController@post');

$r->put('/subscribers/{subscriber:\d+}', 'SubscribersController@put');

$r->delete('/subscribers/{subscriber:\d+}', 'SubscribersController@delete');

//Fields routes
$r->get( '/fields', 'FieldsController@get');

$r->get( '/fields/{field:\d+}', 'FieldsController@get');

$r->post( '/fields', 'FieldsController@post');

$r->put('/fields/{field:\d+}', 'FieldsController@put');

$r->delete('/fields/{field:\d+}', 'FieldsController@delete');

//SubscriberFields routes
$r->get( '/subscribers/{subscriber:\d+}/fields', 'SubscribersFieldsController@get');

$r->post( '/subscribers/{subscriber:\d+}/fields', 'SubscribersFieldsController@post');

$r->put('/subscribers/{subscriber:\d+}/fields/{id:\d+}', 'SubscribersFieldsController@put');

$r->delete('/subscribers/{subscriber:\d+}/fields/{id:\d+}', 'SubscribersFieldsController@delete');
