<?php
 $client = new soapclient("http://central.dtvds.com/index.php?r=central/quote");

       if($client)
        {
            var_dump($client->verify_subscriber('1111'));
        }
