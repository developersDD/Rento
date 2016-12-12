
<?php
$connection = mysql_connect('localhost','root','') or die ('Failed To Connect'.mysql_error());
mysql_select_db('rento_trial',$connection) or die ('Failed To Select Database'.mysql_error());
?>

<?php

function pc_validate($user,$pass) {
        $users = array('admin' => 'sai',
            'password'  => '123');

        if (($users['admin'] == $user) && ($users['password'] == $pass)) {
                return true;
        } else {
                return false;
        }
}