<?php

declare(strict_types=1);

namespace App\Controllers;



class CsrfController
{
    public function getToken()
    {
        // Regenerate token for extra security
        $newToken = bin2hex(random_bytes(32));
        $_SESSION['token'] = $newToken;

        // Return JSON response with the token
        header('Content-Type: application/json');
        echo json_encode(['token' => $newToken]);
        exit;
    }
}
