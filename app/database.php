<?php

use \App\Util\DB;

$db = new DB;

$db_configuration = $app->getContainer()->config->get('db');
$db->addConnection($db_configuration);

$db->setAsGlobal();
$db->bootEloquent();