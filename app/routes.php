<?php
$app->get('/apps', 'ApplicationsController:index');
$app->get('/apps/{name}/users', 'ApplicationsController:displayUsersByApplication');
$app->post('/apps/createApp', 'ApplicationsController:createApplication');
$app->get('/apps/createApp', 'ApplicationsController:createApplication');
$app->get('/apps/ajax/{name}', 'ApplicationsController:ajax');
$app->post('/apps/ajax/{name}', 'ApplicationsController:ajax');
$app->get('/users', 'ApplicationsController:displayUsers');
$app->Post('/users/ajax', 'ApplicationsController:sendEmail');
$app->get('/apps/manage', 'ApplicationsController:manageApplications');