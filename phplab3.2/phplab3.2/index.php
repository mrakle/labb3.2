<?php

require_once("application/controller/Application.php");
require_once("common/view/PageView.php");
require_once("common/view/DebugView.php");

session_start();

\Debug::log("session started ");

$app = new \application\controller\Application();
$pageView = new \common\view\PageView();
//$regClass = new \login\Registrera();

$debug = new \common\view\DebugView();


$page = $app->doFrontPage();
//$regClass = $regClass->regStart();
$debugDump =  $debug->getDebugData();
$page->body .= $debugDump;

echo $pageView->GetXHTML10StrictPage($page);
//echo $regClass->regHtmlForm();