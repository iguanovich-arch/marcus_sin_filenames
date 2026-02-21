<?php
require __DIR__ . '/../core/bootstrap.php';
require __DIR__ . '/../core/layout.php';
require_login();
require_module('configuracion');

$checks = [
    'Documentos comerciales' => [
        'Mostrar impuesto sobre el total',
        'Bloquear precio por debajo del precio mínimo',
        'Bloquear precio por debajo del precio base',
        'Permitir edición/eliminación de facturas',
        'Actualizar precios al crear/editar compras',
    ],
    'Visibilidad de documentos' => [
        'Presupuestos, pedidos y albaranes de venta/trabajo',
        'Gastos, pedidos proveedor, albaranes proveedor, facturas proveedor',
        'Facturas y facturas de abono',
    ],
    'Stock y duplicados' => [
        'Comprobar stock en ventas/trabajos/facturas',
        'Evitar duplicados por NIF, referencia, código barras, número serie',
    ],
    'Firma online y facturar a' => [
        'Firma online de todos los documentos comerciales',
        'Facturar a tercero y precargar email',
    ],
    'App, SAT y correos' => [
        'Modo offline',
        'Editar incidencias cerradas / múltiples documentos',
        'Acuse de lectura en correos',
    ],
    'Finanzas y extras' => [
        'Remesas SEPA, multitarifa, activos en clientes',
        'Aviso de riesgo para clientes, tareas automáticas',
        'Contabilidad, Inbox+OCR, Connect, API',
    ],
];

render_header('Configuración ERP (checks)');
?>
<div class="card"><h2>Parametrización por checks</h2><p>Estos checks se guardan por cliente (tenant) y condicionan la UI, reglas de negocio y APIs.</p></div>
<?php foreach ($checks as $group => $items): ?>
    <div class="card"><h3><?= htmlspecialchars($group) ?></h3>
        <?php foreach ($items as $item): ?>
            <label style="display:block;margin:6px 0"><input type="checkbox" checked> <?= htmlspecialchars($item) ?></label>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>
<?php render_footer(); ?>
