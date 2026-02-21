# ERP SaaS multiempresa (base web + app)

## 1) Análisis funcional solicitado
Se implementa una base modular alineada con la estructura pedida: Inicio, Catálogo, Clientes, Proveedores, Presupuestos, Facturación, Compras, Agenda, Informes y Configuración por checks.

### Módulos troncales
- **Inicio**: cuadro de mando de ventas/compras/facturación y cuota API.
- **Catálogo**: productos, servicios, gastos e inversiones.
- **Clientes / Proveedores**: fichas, contactos y cuentas bancarias.
- **Presupuestos**: materiales + mano de obra y transición documental.
- **Facturación**: facturas/abonos/recibos/libro + opción VeriFactu.
- **Compras**: ciclo completo de compras y remesas SEPA.
- **Agenda**: calendario/tareas/planificación SAT.
- **Informes**: ventas, compras, tesorería, stock, impuestos.

### Parametrización por checks
Se prepara un bloque de checks agrupados para activar/desactivar reglas de negocio por cliente, incluyendo documentos comerciales, stock, duplicados, firma, SAT, correos, finanzas, OCR, Connect y API.

### Roles y permisos
Modelo RBAC (roles + permisos CRUD por recurso) con soporte para perfiles:
- Comercial
- Comercial administrador
- Técnico
- Técnico administrador
- Super administrador

### Planes de suscripción
Se define activación modular por plan (Basic, Medium, Plus), con:
- habilitación de módulos,
- cuota de API mensual,
- posibilidad de cambio de plan,
- bloqueo por impago,
- emisión de factura por pago.

## 2) App móvil (enfoque funcional)
La base web está preparada para servir como backend de app Android/iOS:
- autenticación por tenant,
- permisos por rol,
- modo offline (sincronización posterior),
- módulos dependientes de plan.

## 3) Cumplimiento España
- Diseño preparado para NIF/CIF, impuestos, libros de factura y remesas SEPA.
- VeriFactu activable por cliente desde configuración.
- Facturación electrónica incluida en el roadmap operativo.

## 4) Integración de cobros (CaixaBank)
Se deja estructura de configuración para pasarela española (CaixaBank), con flujo recomendado:
1. Alta de comercio y credenciales de producción.
2. Cobro de suscripción.
3. Registro de transacción.
4. Emisión automática de factura SaaS.
5. Reintentos / bloqueo automático por impago.

## 5) Plan de negocio resumido
1. **Lanzamiento (0-3 meses)**: MVP Basic + onboarding + soporte.
2. **Escalado (3-9 meses)**: Medium/Plus, Connect, OCR, automatizaciones.
3. **Expansión (9-18 meses)**: verticalización por sector, partners y API marketplace.

### KPIs clave
- MRR, churn, CAC/LTV.
- Tasa de cobro exitoso.
- Uso de módulos por plan.
- Consumo API por cliente.
- Tiempo medio de implantación por empresa.

### Operación comercial
- Demo guiada + prueba.
- Implantación con migración CSV.
- Formación por rol.
- Soporte continuo y revisiones trimestrales.
