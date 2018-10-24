<?php
$url_host = filter_input(INPUT_SERVER, 'HTTP_HOST');
define('pg', "https://$url_host");
