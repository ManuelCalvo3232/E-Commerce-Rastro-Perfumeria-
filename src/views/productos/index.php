<main>
    <div class="page-header">
        <h1>Listado de Productos</h1>
        <a href="/productos/create" class="btn">+ Nuevo producto</a>
    </div>

    <?php if (empty($productos)): ?>
        <p>Todavía no hay productos cargados.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Marca</th>
                    <th>Activo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?= html($producto['nombre']) ?></td>
                        <td><?= html($producto['marca_nombre']) ?></td>
                        <td><?= $producto['activo'] ? 'Sí' : 'No' ?></td>
                        <td class="actions">
                            <a href="/productos/<?= html((string) $producto['id']) ?>">Ver</a>
                            <a href="/productos/update/<?= html((string) $producto['id']) ?>">Editar</a>
                            <button type="button" class="btn-eliminar" data-id="<?= html((string) $producto['id']) ?>">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <script>
        document.querySelectorAll('.btn-eliminar').forEach(function (btn) {
            btn.addEventListener('click', async function () {
                if (!confirm('¿Eliminar este producto?')) return;

                const id = btn.getAttribute('data-id');
                const response = await fetch('/productos/' + id, { method: 'DELETE' });

                if (response.redirected) {
                    window.location.href = response.url;
                }
            });
        });
    </script>
</main>