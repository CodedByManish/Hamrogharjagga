<?php
require_once "../../config/cors.php";
require_once "../../helpers/JsonHelper.php";

use App\Helpers\JsonHelper;

session_start();
session_unset();
session_destroy();

JsonHelper::success("Logged out successfully!", null, "index.php");