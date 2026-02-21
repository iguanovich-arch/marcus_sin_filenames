<?php
require __DIR__ . '/../core/bootstrap.php';
require __DIR__ . '/../core/layout.php';
require_login();
require_module('facturacion');

render_header('Facturación');
?>
<div class="card"><h2>Facturación</h2><p>Facturación desde presupuesto o factura directa, abonos, recibos, libro de facturas y compatibilidad VeriFactu configurable.</p></div>
<?php render_footer(); ?>
