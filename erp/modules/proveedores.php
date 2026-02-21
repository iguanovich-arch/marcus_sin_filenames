<?php
require __DIR__ . '/../core/bootstrap.php';
require __DIR__ . '/../core/layout.php';
require_login();
require_module('proveedores');

render_header('Proveedores');
?>
<div class="card"><h2>Proveedores</h2><p>Gestión de proveedores, cuentas bancarias, libros de facturas recibidas y tickets.</p></div>
<?php render_footer(); ?>
