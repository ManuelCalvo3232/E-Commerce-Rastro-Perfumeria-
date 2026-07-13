<main>
    <section class="hero">
        <h1>Descubrí tu fragancia ideal</h1>
        <p>Perfumes originales y decants a precios accesibles.</p>
    </section>

    <section class="productos-destacados">
        <h2>Destacados</h2>
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
</main>