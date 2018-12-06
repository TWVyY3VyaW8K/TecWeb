<?php
//destroy the session
session_start();
unset($_SESSION);
session_destroy();
echo "success";
die;
?>