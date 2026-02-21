<?php
session_start();

$config = require __DIR__ . '/config.php';

function app_config(string $key, $default = null)
{
    global $config;
    return $config[$key] ?? $default;
}

function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $db = app_config('db');
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $db['host'], $db['name'], $db['charset']);
        $pdo = new PDO($dsn, $db['user'], $db['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    return $pdo;
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function require_login(): void
{
    if (!current_user()) {
        header('Location: /erp/login.php');
        exit;
    }
}

function tenant_id(): ?int
{
    return current_user()['client_id'] ?? null;
}

function has_role_permission(string $resource, string $action): bool
{
    $user = current_user();
    if (!$user) {
        return false;
    }

    if (!empty($user['is_super_admin'])) {
        return true;
    }

    $stmt = db()->prepare(
        'SELECT 1
         FROM role_permissions rp
         INNER JOIN roles r ON r.id = rp.role_id
         WHERE r.id = :role_id
           AND r.client_id = :client_id
           AND rp.resource = :resource
           AND rp.action = :action
         LIMIT 1'
    );
    $stmt->execute([
        'role_id' => $user['role_id'],
        'client_id' => $user['client_id'],
        'resource' => $resource,
        'action' => $action,
    ]);

    return (bool) $stmt->fetchColumn();
}

function module_enabled_for_client(int $clientId, string $moduleCode): bool
{
    $stmt = db()->prepare(
        'SELECT cm.enabled
         FROM client_modules cm
         INNER JOIN modules m ON m.id = cm.module_id
         WHERE cm.client_id = :client_id
           AND m.code = :module_code
         LIMIT 1'
    );
    $stmt->execute([
        'client_id' => $clientId,
        'module_code' => $moduleCode,
    ]);

    return (bool) $stmt->fetchColumn();
}

function require_module(string $moduleCode): void
{
    $clientId = tenant_id();
    if (!$clientId || !module_enabled_for_client($clientId, $moduleCode)) {
        http_response_code(403);
        exit('Módulo no disponible para tu plan actual.');
    }
}

function api_quota_remaining(int $clientId): int
{
    $stmt = db()->prepare('SELECT api_quota_monthly - api_calls_current_month FROM clients WHERE id = :id');
    $stmt->execute(['id' => $clientId]);
    return (int) $stmt->fetchColumn();
}
