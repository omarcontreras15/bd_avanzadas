<?php

include_once "./app/router/router.php";
include_once "./app/controller/user.php";
include_once "./app/model/user.php";

$router = new Router();
$router->router();

?>