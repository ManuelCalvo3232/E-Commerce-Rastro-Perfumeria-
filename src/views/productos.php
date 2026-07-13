<main>
    <section class="catalogo-header">
        <h1>Catálogo</h1>
        <p>Explorá nuestras fragancias por categoría.</p>
    </section>

    <?php foreach ($catalogo as $categoria => $productos): ?>
        <section class="categoria">
            <h2><?= html($categoria) ?></h2>
            <div class="grid">
                <?php foreach ($productos as $producto): ?>
                    <article class="producto-card">
                        <img src="<?= html($producto['imagen']) ?>" alt="<?= html($producto['nombre']) ?>">
                        <h3><?= html($producto['marca']) ?> — <?= html($producto['nombre']) ?></h3>
                        <p>$<?= html((string) $producto['precio']) ?> / <?= html($producto['ml']) ?>ml</p>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endforeach; ?>
</main>