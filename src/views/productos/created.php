<main class="form-container">
    <h1>Producto creado</h1>

    <article class="producto-detalle">
        <h2><?= html($producto['marca']) ?> — <?= html($producto['nombre']) ?></h2>
        <p><strong>Categoría:</strong> <?= html($producto['categoria']) ?></p>
        <p><strong>Precio 100ml:</strong> $<?= html($producto['precioEntero']) ?></p>
        <p><strong>Precio 10ml:</strong> $<?= html($producto['precio10ml']) ?></p>
        <p><strong>Precio 5ml:</strong> $<?= html($producto['precio5ml']) ?></p>
        <?php if ($producto['descripcion'] !== ""): ?>
            <p><strong>Descripción:</strong> <?= html($producto['descripcion']) ?></p>
        <?php endif; ?>
    </article>

    <a href="/create/productos">&larr; Cargar otro producto</a>
</main>
