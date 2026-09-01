<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test DB Connection
try {
    $user = \App\Models\User::first();
    if ($user) {
        $user->load('role');
        $resource = new \App\Http\Resources\UserResource($user);
        echo json_encode(['user' => $resource->resolve()]);
    } else {
        echo 'No user';
    }
} catch (\Exception $e) {
    echo "DB Error: " . $e->getMessage();
}
