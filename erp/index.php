<?php
require __DIR__ . '/core/bootstrap.php';
require __DIR__ . '/core/layout.php';
require_login();

render_header('Inicio / Dashboard');
$clientId = tenant_id();
$remaining = api_quota_remaining($clientId);
?>
<div class="card"><h2>Resumen ERP</h2><p>Dashboard de ventas, compras y facturación del cliente actual.</p><span class="pill">API restante este mes: <?= (int) $remaining ?></span></div>
<div class="card">
<table>
<tr><th>Métrica</th><th>Valor</th></tr>
<tr><td>Ventas mes</td><td>€ 12.430</td></tr>
<tr><td>Compras mes</td><td>€ 7.920</td></tr>
<tr><td>Facturas emitidas</td><td>87</td></tr>
<tr><td>Facturas pendientes de cobro</td><td>11</td></tr>
</table>
</div>
<?php render_footer(); ?>
