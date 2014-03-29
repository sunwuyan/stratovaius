<?php
    require_once 'phprunconfig.php';
    $client=new SoapClient('http://localhost/y/testdriver/index.php?r=site/quote');
    echo $client->getPrice('10.68.188.78'.'&'.'2014-03-15 16:00:00'.'&'.'2014-03-15 16:30:00');

?>