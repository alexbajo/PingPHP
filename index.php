<?php 


function ping($ip_addr){
        echo "<br>";
      if (!exec("ping -n 1 -w 1 ".$ip_addr." 2>NUL > NUL && (echo 0) || (echo 1)")){
                echo "El host existe -" . $ip_addr;
                return true;
        } else {
            echo "El host no existe -" . $ip_addr;
            return false;
        }

    }
    

    $ip_addr3 = "10.100.21.11";

ping($ip_addr3);
$ip_addr2 = "10.100.21.1";

ping($ip_addr2);