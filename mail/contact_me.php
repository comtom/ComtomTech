<?php
// Check for empty fields

if(empty($_POST['name'])  		||
   empty($_POST['email']) 		||
   empty($_POST['phone']) 		||
   empty($_POST['message'])	||
   !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
   {
	echo "Por favor, complete todos los campos.";
	return false;
   }

$name = $_POST['name'];
$email_address = $_POST['email'];
$phone = $_POST['phone'];
$message = $_POST['message'];

require 'vendor/autoload.php';

use SparkPost\SparkPost;
use GuzzleHttp\Client;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
 
$httpClient = new GuzzleAdapter(new Client());
$sparky = new SparkPost($httpClient, ['key' => '70c47502c2cab9fb073e66587098bb23899b2339']);
 
$sparky->setOptions(['async' => false]);
$results = $sparky->transmissions->post([
  'options' => [
    'sandbox' => false
  ],
  'content' => [
    'from' => 'noreply@comtomtech.com',
    'subject' => "[Comtom Tech] Contacto desde de la web: $name",
    'html' => "<html><body><h2>You have received a new message from your website contact form.</h2>
        <p>Here are the details:
        <br>Name: $name
        <br>Email: $email_address
        <br>Phone: $phone
        <br>Message:
        <br>$message
        </p></body></html>"
  ],
  'recipients' => [
    ['address' => ['email'=>'ventas@comtomtech.com']]
  ]
]);

return true;
?>
