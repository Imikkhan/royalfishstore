<?php
$dbPath = __DIR__ . '/database/database.sqlite';
$pdo = new PDO('sqlite:' . $dbPath);
$stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'");
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo "Tables in " . $dbPath . ":\n";
print_r($tables);

if (!in_array('riders', $tables)) {
    echo "Creating riders table manually...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS riders (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(255) NOT NULL,
        phone VARCHAR(255) NOT NULL UNIQUE,
        vehicle_type VARCHAR(255) DEFAULT 'Bike',
        vehicle_number VARCHAR(255) NOT NULL,
        operating_pincodes TEXT,
        status VARCHAR(255) DEFAULT 'Available',
        is_active TINYINT(1) DEFAULT 1,
        created_at DATETIME,
        updated_at DATETIME
    )");
    
    $pdo->exec("INSERT OR IGNORE INTO riders (name, phone, vehicle_type, vehicle_number, operating_pincodes, status, is_active, created_at, updated_at) VALUES 
        ('Ramesh Shinde', '9820198201', 'Motorbike', 'MH-01-AX-9911', '[\"400001\",\"400002\"]', 'Available', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
        ('Suresh Patil', '9820298202', 'EV Delivery Van', 'MH-02-EV-4422', '[\"400003\",\"400004\"]', 'Available', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
    ");
    echo "Riders table created and seeded successfully!\n";
}

// Check orders table columns
$stmt2 = $pdo->query("PRAGMA table_info(orders)");
$cols = array_column($stmt2->fetchAll(PDO::FETCH_ASSOC), 'name');
echo "Orders table columns:\n";
print_r($cols);

if (!in_array('rider_id', $cols)) {
    echo "Adding rider_id and logistics columns to orders table...\n";
    $pdo->exec("ALTER TABLE orders ADD COLUMN rider_id INTEGER NULL");
    $pdo->exec("ALTER TABLE orders ADD COLUMN shipment_status VARCHAR(255) DEFAULT 'Pending Assignment'");
    $pdo->exec("ALTER TABLE orders ADD COLUMN tracking_number VARCHAR(255) NULL");
    $pdo->exec("ALTER TABLE orders ADD COLUMN dispatched_at DATETIME NULL");
    $pdo->exec("ALTER TABLE orders ADD COLUMN delivered_at DATETIME NULL");
    $pdo->exec("ALTER TABLE orders ADD COLUMN delivery_notes TEXT NULL");
    echo "Orders logistics columns added successfully!\n";
}
