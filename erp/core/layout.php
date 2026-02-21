<?php
function render_header(string $title): void
{
    $user = current_user();
    echo '<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">';
    echo '<title>' . htmlspecialchars($title) . '</title>';
    echo '<style>body{font-family:Arial;margin:0;background:#f3f4f6}header{background:#111827;color:#fff;padding:12px 16px}nav{width:240px;position:fixed;top:48px;bottom:0;overflow:auto;background:#1f2937;padding:12px}nav a{display:block;color:#e5e7eb;padding:8px;text-decoration:none;border-radius:6px}nav a:hover{background:#374151}.main{margin-left:264px;padding:18px}.card{background:#fff;padding:14px;border-radius:8px;margin-bottom:12px;box-shadow:0 1px 2px rgba(0,0,0,.08)}table{width:100%;border-collapse:collapse}td,th{padding:8px;border-bottom:1px solid #e5e7eb}.pill{display:inline-block;background:#dbeafe;color:#1e3a8a;padding:2px 8px;border-radius:999px;font-size:12px}</style>';
    echo '</head><body>';
    echo '<header><strong>ERP Multiempresa</strong> · ' . htmlspecialchars($title) . ' <span style="float:right">' . htmlspecialchars($user['name'] ?? 'Invitado') . '</span></header>';
    echo '<nav>';
    $links = [
        'Inicio' => '/erp/index.php',
        'Catálogo' => '/erp/modules/catalogo.php',
        'Clientes' => '/erp/modules/clientes.php',
        'Proveedores' => '/erp/modules/proveedores.php',
        'Presupuesto' => '/erp/modules/presupuestos.php',
        'Facturación' => '/erp/modules/facturacion.php',
        'Compras' => '/erp/modules/compras.php',
        'Agenda' => '/erp/modules/agenda.php',
        'Informes' => '/erp/modules/informes.php',
        'Configuración checks' => '/erp/modules/configuracion.php',
    ];
    foreach ($links as $label => $href) {
        echo '<a href="' . $href . '">' . htmlspecialchars($label) . '</a>';
    }
    echo '<a href="/erp/logout.php">Cerrar sesión</a>';
    echo '</nav><div class="main">';
}

function render_footer(): void
{
    echo '</div></body></html>';
}
