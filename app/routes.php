<?php
$app->get('/apps', 'ApplicationsController:index');
$app->get('/apps/{name}/users', 'ApplicationsController:displayUsersByApplication');
$app->post('/apps/createApp', 'ApplicationsController:createApplication');
$app->get('/apps/createApp', 'ApplicationsController:createApplication');