<?php
// $host    = 'sql211.byethost22.com';
// $port    = '3306';
// $db      = 'b22_39125661_delicia_db';
// $user    = 'b22_39125661';
// $pass    = 's@Ts7AP2L.pwPHx';
// $charset = 'utf8mb4';

$host    = '127.0.0.1';
$port    = '3306';
$db      = 'delicia_db';
$user    = 'root';
$pass    = 'Mandil01!!';
$charset = 'utf8mb4';

$dsn = "mysql:host={$host};port={$port};dbname={$db};charset={$charset}";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $conn = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo "❌ Connection failed: " . $e->getMessage();
    exit;
}
?>