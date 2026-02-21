<?php
require __DIR__ . '/../erp/core/bootstrap.php';

if (!current_user() || empty(current_user()['is_super_admin'])) {
    http_response_code(403);
    exit('Acceso restringido a Super Admin.');
}

$clients = db()->query('SELECT c.id, c.name, c.status, p.name AS plan_name, c.api_quota_monthly, c.api_calls_current_month FROM clients c LEFT JOIN plans p ON p.id = c.plan_id ORDER BY c.id DESC LIMIT 100')->fetchAll();
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><title>Super Admin ERP</title><style>body{font-family:Arial;padding:20px;background:#f9fafb}.card{background:#fff;padding:16px;border-radius:8px}table{width:100%;border-collapse:collapse}th,td{padding:8px;border-bottom:1px solid #e5e7eb}.blocked{color:#b91c1c;font-weight:700}</style></head><body>
<h1>Super Admin SaaS ERP</h1>
<div class="card">
<h2>Clientes, plan, pagos y uso API</h2>
<table><tr><th>ID</th><th>Cliente</th><th>Plan</th><th>Estado</th><th>API usada</th><th>API cuota</th></tr>
<?php foreach ($clients as $client): ?>
<tr>
<td><?= (int) $client['id'] ?></td>
<td><?= htmlspecialchars($client['name']) ?></td>
<td><?= htmlspecialchars($client['plan_name'] ?? 'Sin plan') ?></td>
<td class="<?= $client['status'] !== 'active' ? 'blocked' : '' ?>"><?= htmlspecialchars($client['status']) ?></td>
<td><?= (int) $client['api_calls_current_month'] ?></td>
<td><?= (int) $client['api_quota_monthly'] ?></td>
</tr>
<?php endforeach; ?>
</table>
<p>Desde este panel puedes bloquear morosos, cambiar planes (Basic/Medium/Plus), revisar facturación y limitar APIs por suscripción.</p>
</div>
</body></html>
