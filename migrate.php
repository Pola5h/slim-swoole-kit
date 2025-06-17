<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/bootstrap.php';

use Illuminate\Database\Capsule\Manager as Capsule;

try {
    // Create users table
    Capsule::schema()->dropIfExists('users');
    Capsule::schema()->create('users', function ($table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->timestamps();
    });

    echo "✅ Database tables created successfully!\n";
    
    // Insert sample data
    $users = [
        ['name' => 'John Doe', 'email' => 'john@example.com'],
        ['name' => 'Jane Smith', 'email' => 'jane@example.com'],
        ['name' => 'Bob Johnson', 'email' => 'bob@example.com'],
    ];

    foreach ($users as $userData) {
        Capsule::table('users')->insert(array_merge($userData, [
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]));
    }

    echo "✅ Sample data inserted successfully!\n";
    echo "🚀 Database setup complete!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
