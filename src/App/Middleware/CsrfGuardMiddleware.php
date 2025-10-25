<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;

class CsrfGuardMiddleware implements MiddlewareInterface
{
    public function process(callable $next)
    {
        $requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);
        $validMethods = ['POST', 'PATCH', 'DELETE'];

        if ($_SERVER['REQUEST_URI'] === '/csrf-token') {
            $next();
            return;
        }
        // Skip CSRF validation for non-state-changing methods
        if (!in_array($requestMethod, $validMethods)) {
            $next();
            return;
        }

        $token = $this->getTokenFromRequest();

        // Validate CSRF token
        if (!$token || !isset($_SESSION['token']) || $_SESSION['token'] !== $token) {
            $this->handleInvalidToken();
            return;
        }

        // Generate new token for next request (token rotation)
        $newToken = bin2hex(random_bytes(32));
        $_SESSION['token'] = $newToken;

        // Make new token available to the response
        $_SESSION['new_csrf_token'] = $newToken;

        $next();
    }

    private function getTokenFromRequest(): ?string
    {
        // Check JSON body first (for AJAX requests)
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (strpos($contentType, 'application/json') !== false) {
            $jsonInput = file_get_contents('php://input');
            if ($jsonInput) {
                $input = json_decode($jsonInput, true);
                if (is_array($input) && isset($input['token'])) {
                    return $input['token'];
                }
            }
        }

        // Check HTTP headers
        $headers = $this->getAllHeaders();

        // Check for X-CSRF-TOKEN header (case-insensitive)
        foreach (['X-CSRF-TOKEN', 'X-Csrf-Token', 'x-csrf-token'] as $headerName) {
            if (isset($headers[$headerName])) {
                return $headers[$headerName];
            }
        }

        // Check POST data (for regular form submissions)
        if (isset($_POST['token'])) {
            return $_POST['token'];
        }

        return null;
    }

    private function getAllHeaders(): array
    {
        // Use getallheaders() if available, otherwise parse from $_SERVER
        if (function_exists('getallheaders')) {
            return getallheaders() ?: [];
        }

        // Fallback: extract headers from $_SERVER
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $headerName = str_replace('_', '-', substr($key, 5));
                $headers[$headerName] = $value;
            }
        }

        return $headers;
    }

    private function isAjaxRequest(): bool
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        $acceptHeader = $_SERVER['HTTP_ACCEPT'] ?? '';

        return strpos($contentType, 'application/json') !== false
            || strpos($acceptHeader, 'application/json') !== false
            || isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest');
    }

    private function handleInvalidToken(): void
    {
        if ($this->isAjaxRequest()) {
            // Return JSON response for AJAX requests
            http_response_code(419); // CSRF Token Mismatch
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'CSRF token mismatch. Please refresh the page.',
                'error' => 'csrf_token_mismatch',
                'code' => 419
            ]);
            exit;
        }

        // Redirect for regular form submissions
        redirectTo('/');
        exit;
    }
}
