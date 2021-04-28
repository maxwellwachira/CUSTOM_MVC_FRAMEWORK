<?php
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../vendor/mailjet/vendor/autoload.php';


use app\controllers\PagesController; 
use app\controllers\MailController;
use app\controllers\AuthController;
use app\controllers\CoopconnectController;
use app\core\Application;
use app\core\Model;
use app\models\RegisterModel;

$app = new Application(dirname(__DIR__));

$app->router->get('/', [PagesController::class, 'home']);
$app->router->get('/about', [PagesController::class, 'about']);
$app->router->get('/contact', [PagesController::class, 'contact']);
$app->router->post('/contact', [MailController::class, 'sendContactform']);
$app->router->get('/login', [AuthController::class, 'loginGet']);
$app->router->post('/login', [AuthController::class, 'loginPost']);
$app->router->get('/register', [AuthController::class, 'registerGet']);
$app->router->post('/register', [AuthController::class, 'registerPost']);
$app->router->get('/forgot-password', [AuthController::class, 'forgotPasswordGet']);
$app->router->post('/forgot-password', [AuthController::class, 'forgotPasswordPost']);
$app->router->get('/password-reset', [AuthController::class, 'passwordResetGet']);
$app->router->post('/password-reset', [AuthController::class, 'passwordResetPost']);
$app->router->get('/logout', [AuthController::class, 'logout']);
$app->router->get('/otp-verification', [AuthController::class, 'otpGet']);
$app->router->post('/otp-verification', [AuthController::class, 'otpPost']);
$app->router->get('/coop-connect', [CoopconnectController::class, 'coopGet']);
$app->router->post('/coop-connect', [CoopconnectController::class, 'coopPost']);

$app->run();
?> 