<?php
$mysqli = new mysqli('127.0.0.1','root','','centinela',3306);
if ($mysqli->connect_error) {
    echo "connect error: " . $mysqli->connect_error . PHP_EOL;
    exit(1);
}
$migrations = [
    '2025_11_06_000001_create_regiones_table',
    '2025_11_06_000002_create_fotosreportes_table',
    '2025_11_06_000003_create_reportes_table',
    '2025_11_06_000004_create_alertas_table',
    '2025_11_06_000005_create_comentarios_table',
    '2025_11_06_000006_create_emergencias_table',
    '2025_11_06_000007_add_fields_to_users_table',
];
foreach ($migrations as $mig) {
    $mig_esc = $mysqli->real_escape_string($mig);
    $res = $mysqli->query("SELECT COUNT(*) as c FROM migrations WHERE migration = '$mig_esc'");
    if ($res) {
        $row = $res->fetch_assoc();
        if ($row['c'] == 0) {
            $stmt = $mysqli->prepare("INSERT INTO migrations (migration, batch) VALUES (?, 1)");
            $stmt->bind_param('s', $mig);
            $stmt->execute();
            echo "Inserted: $mig\n";
        } else {
            echo "Already present: $mig\n";
        }
    } else {
        echo "Error checking migration: " . $mysqli->error . "\n";
    }
}
echo "Done\n";
