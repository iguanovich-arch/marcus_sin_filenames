<?php
require __DIR__ . '/core/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = db()->prepare('SELECT u.*, c.status AS client_status, c.plan_id, c.api_quota_monthly, c.api_calls_current_month FROM users u INNER JOIN clients c ON c.id = u.client_id WHERE u.email = :email LIMIT 1');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        if ($user['client_status'] !== 'active') {
            $error = 'Cliente bloqueado por impago o baja.';
        } else {
            $_SESSION['user'] = [
                'id' => (int) $user['id'],
                'client_id' => (int) $user['client_id'],
                'role_id' => (int) $user['role_id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'is_super_admin' => false,
            ];
            header('Location: /erp/index.php');
            exit;
        }
    } else {
        $error = 'Credenciales no válidas.';
    }
}
?>
<!doctype html>
<html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Login ERP</title>
<style>body{font-family:Arial;background:#f3f4f6;display:flex;align-items:center;justify-content:center;height:100vh}.box{background:#fff;padding:20px;border-radius:8px;min-width:320px}.error{color:#b91c1c}</style></head>
<body><form class="box" method="post"><h1>Acceso ERP</h1>
<?php if (!empty($error)): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
<label>Email<input type="email" name="email" required></label><br><br>
<label>Contraseña<input type="password" name="password" required></label><br><br>
<button type="submit">Entrar</button></form></body></html>
