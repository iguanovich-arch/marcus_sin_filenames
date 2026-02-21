<?php
require __DIR__ . '/../core/bootstrap.php';
require __DIR__ . '/../core/layout.php';
require_login();
require_module('compras');

render_header('Compras');
?>
<div class="card"><h2>Compras</h2><p>Módulo completo de compras: pedidos proveedor, albaranes proveedor, facturas proveedor, pagos y remesas SEPA.</p></div>
<?php render_footer(); ?>
