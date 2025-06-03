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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PingPHP</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; margin: 2em; }
        h1 { text-align: center; }
        form.add-form { text-align: center; margin-bottom: 1em; }
        form.add-form input[type=text] { padding: 0.5em; width: 200px; }
        form.add-form input[type=submit] { padding: 0.5em 1em; }
        .device { background: #fff; border: 1px solid #ddd; padding: 0.5em; margin-bottom: 0.5em; }
        .device span { font-weight: bold; }
        .device form { display: inline; }
    </style>
</head>
<body>
    <h1>Ping de dispositivos</h1>

    <form class="add-form" method="POST" action="">
        <input type="text" name="ip_to_add" placeholder="Introduce una IP">
        <input type="submit" value="Añadir IP">
    </form>

    <div class="devices">
        <?php foreach ($ipAddresses as $ip): ?>
            <div class="device">
                <?php echo ping($ip); ?>
                <form method="POST" action="">
                    <input type="hidden" name="ip_to_delete" value="<?php echo $ip; ?>">
                    <input type="submit" value="Borrar">
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
