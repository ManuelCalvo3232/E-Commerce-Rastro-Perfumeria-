<main>
    <h1>Listado de Productos</h1>
    <div class="grid">
        <?php foreach ($productos as $producto): ?>
            <a href="/productos/<?= html((string) $producto['id']) ?>" class="producto-card">
                <img src="<?= html($producto['imagen']) ?>" alt="<?= html($producto['nombre']) ?>">
                <h3><?= html($producto['marca']) ?> — <?= html($producto['nombre']) ?></h3>
                <p>$<?= html((string) $producto['precio']) ?> / <?= html($producto['ml']) ?>ml</p>
            </a>
        <?php endforeach; ?>
    </div>
</main>