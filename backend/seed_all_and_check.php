<?php
use App\Models\User;
use App\Models\Role;
use App\Models\Rider;
use Illuminate\Support\Facades\Hash;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Users count: " . User::count() . "\n";
echo "Roles count: " . Role::count() . "\n";
echo "Riders count: " . Rider::count() . "\n";

// Force seed database if empty or superadmin missing
$admin = User::where('email', 'admin@royalfish.com')->first();
if (!$admin) {
    echo "Seeding Super Admin user...\n";
    $superRole = Role::firstOrCreate(
        ['slug' => 'super-admin'],
        ['name' => 'Super Admin', 'permissions' => json_encode(['*'])]
    );

    $admin = User::create([
        'name' => 'Super Admin',
        'email' => 'admin@royalfish.com',
        'phone' => '9999999999',
        'password' => Hash::make('password'),
        'role_id' => $superRole->id,
    ]);
    echo "Super Admin Created: admin@royalfish.com / password\n";
} else {
    $admin->password = Hash::make('password');
    $admin->save();
    echo "Admin password reset to 'password' successfully!\n";
}

// Check Riders credentials
$r1 = Rider::where('phone', '9820198201')->first();
if (!$r1) {
    $r1 = Rider::create([
        'name' => 'Ramesh Shinde',
        'phone' => '9820198201',
        'email' => 'ramesh@royalfish.com',
        'password' => Hash::make('password'),
        'vehicle_type' => 'Motorbike',
        'vehicle_number' => 'MH-01-AX-9911',
        'operating_pincodes' => json_encode(['400001', '400002']),
        'status' => 'Available',
        'earnings_per_delivery' => 50,
        'is_active' => true
    ]);
    echo "Ramesh rider created: ramesh@royalfish.com / password\n";
} else {
    $r1->email = 'ramesh@royalfish.com';
    $r1->password = Hash::make('password');
    $r1->save();
    echo "Ramesh rider password set to 'password'\n";
}

$r2 = Rider::where('phone', '9820298202')->first();
if (!$r2) {
    $r2 = Rider::create([
        'name' => 'Suresh Patil',
        'phone' => '9820298202',
        'email' => 'suresh@royalfish.com',
        'password' => Hash::make('password'),
        'vehicle_type' => 'EV Delivery Van',
        'vehicle_number' => 'MH-02-EV-4422',
        'operating_pincodes' => json_encode(['400003', '400004']),
        'status' => 'Available',
        'earnings_per_delivery' => 50,
        'is_active' => true
    ]);
    echo "Suresh rider created: suresh@royalfish.com / password\n";
} else {
    $r2->email = 'suresh@royalfish.com';
    $r2->password = Hash::make('password');
    $r2->save();
    echo "Suresh rider password set to 'password'\n";
}
