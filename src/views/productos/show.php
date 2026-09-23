<main class="form-container">
    <h1><?= html($producto['nombre']) ?></h1>

    <article class="producto-detalle">
        <p><strong>Marca:</strong> <?= html($producto['marca_nombre']) ?></p>
        <p><strong>Activo:</strong> <?= $producto['activo'] ? 'Sí' : 'No' ?></p>
        <?php if (!empty($producto['notas_olfativas'])): ?>
            <p><strong>Notas olfativas:</strong> <?= html($producto['notas_olfativas']) ?></p>
        <?php endif; ?>
        <?php if (!empty($producto['descripcion'])): ?>
            <p><strong>Descripción:</strong> <?= html($producto['descripcion']) ?></p>
        <?php endif; ?>
        <p><strong>Creado:</strong> <?= html($producto['creado_en']) ?></p>
    </article>

    <div class="actions">
        <a href="/productos/update/<?= html((string) $producto['id']) ?>">Editar</a>
        <a href="/productos/">&larr; Volver al listado</a>
        <button type="button" class="btn-eliminar" data-id="<?= html((string) $producto['id']) ?>">Eliminar</button>
    </div>

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