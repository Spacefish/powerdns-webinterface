<?php

include_once 'lib/Base.php';

$app = Application::bootstrap();
$app->Dispatcher->dispatch();

?>
