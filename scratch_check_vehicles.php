<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/Core/Database.php';

$db = App\Core\Database::getConnection();
echo "--- vehicles ---\n";
print_r($db->query("SELECT * FROM vehicles")->fetchAll(PDO::FETCH_ASSOC));

echo "--- vehicle_fuel_types JOIN system_catalogs ---\n";
print_r($db->query("SELECT vft.vehicle_id, v.plate, sc.name as catalog_fuel FROM vehicle_fuel_types vft JOIN vehicles v ON v.id = vft.vehicle_id JOIN system_catalogs sc ON sc.id = vft.fuel_type_id")->fetchAll(PDO::FETCH_ASSOC));
