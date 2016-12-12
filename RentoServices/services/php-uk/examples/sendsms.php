<?php
require('../textlocal.class.php');

$textlocal = new Textlocal('rohankawade222@gmail.com', 'c9963114db8e1105ab875cdb14e55dbfd627f8a7');

$numbers = array(9561313954);
$sender = 'Textlocal';
$message = 'This is a message';

try {
    $result = $textlocal->sendSms($numbers, $message, $sender);
    print_r($result);
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}
?>