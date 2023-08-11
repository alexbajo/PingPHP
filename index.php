<?php 

function ping($ip_addr) {
    if (!exec("ping -n 1 -w 1 " . $ip_addr . " 2>NUL > NUL && (echo 0) || (echo 1)")) {
        return "<span style='color:green'>" . $ip_addr . "</span>";
    } else {
        return "<span style='color:red'>" . $ip_addr . "</span>";
    }
}

// Si se envía una IP a agregar.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ip_to_add'])) {
    $newIp = filter_var($_POST['ip_to_add'], FILTER_VALIDATE_IP);
    if ($newIp) {
        file_put_contents('devices.txt', $newIp . "\n", FILE_APPEND);
    }
}

// Si se envía una IP a eliminar.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ip_to_delete'])) {
    $ipToDelete = $_POST['ip_to_delete'];
    $ipAddresses = file('devices.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (($key = array_search($ipToDelete, $ipAddresses)) !== false) {
        unset($ipAddresses[$key]);
        file_put_contents('devices.txt', implode("\n", $ipAddresses));
    }
}

// Leemos las IPs desde el archivo.
$ipAddresses = file('devices.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

?>

<form method="POST" action="">
    <input type="text" name="ip_to_add" placeholder="Introduce una IP">
    <input type="submit" value="Añadir IP">
</form>

<?php
// Hacemos ping a cada IP y mostramos un botón para eliminarla.
foreach ($ipAddresses as $ip) {
    echo ping($ip);
    echo " <form method='POST' action='' style='display:inline;'><input type='hidden' name='ip_to_delete' value='{$ip}'><input type='submit' value='Borrar'></form><br>";
}
?>
