<?php

require_once 'vendor/autoload.php';

use App\Models\User;

$user = User::where('email', 'test@example.com')->first();
if ($user) {
    $token = $user->createToken('test-token')->plainTextToken;
    echo $token;
} else {
    echo "User not found";
}
