<?php

use SONFin\Application;
use SONFin\Plugins\AuthPlugin;
use SONFin\Plugins\DbPlugin;
use SONFin\Plugins\RoutePlugin;
use SONFin\Plugins\ViewPlugin;
use SONFin\ServiceContainer;

require_once __DIR__ .'/../vendor/autoload.php';
require_once __DIR__ .'/../src/helpers.php';

$serviceContainer = new ServiceContainer();
$app = new Application($serviceContainer);

$app->plugin(new RoutePlugin());
$app->plugin(new ViewPlugin());
$app->plugin(new DbPlugin());
$app->plugin(new AuthPlugin());

require_once __DIR__ .'/../src/controllers/statements.php';
require_once __DIR__ .'/../src/controllers/category-costs.php';
require_once __DIR__ .'/../src/controllers/bill-receives.php';
require_once __DIR__ .'/../src/controllers/bill-pays.php';
require_once __DIR__ .'/../src/controllers/users.php';
require_once __DIR__ .'/../src/controllers/auth.php';

$app->start();