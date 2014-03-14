<?php
    require_once 'phprunconfig.php';
    $client=new SoapClient('http://localhost/y/testdriver/index.php?r=site/quote');
    echo $client->getPrice('10.68.184.198'.'&'.'2014-03-08 10:00:00'.'&'.'2014-03-08 13:00:00');

?>