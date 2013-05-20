<?php

include_once 'lib/Base.php';

$app = Application::bootstrap();
$i = new Install($app);
$i->updateSchema();