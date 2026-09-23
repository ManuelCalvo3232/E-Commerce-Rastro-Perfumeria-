<main class="form-container">
    <h1>Nuevo producto</h1>

    <form action="/productos" method="post" class="form">
        <fieldset>
            <legend>Información del producto</legend>

            <div>
                <label for="marca_id">Marca:</label>
                <select id="marca_id" name="marca_id" required>
                    <option value="">Seleccionar...</option>
                    <?php foreach ($marcas as $marca): ?>
                        <option value="<?= html((string) $marca['id']) ?>"
                            <?= (($old['marca_id'] ?? '') == $marca['id']) ? 'selected' : '' ?>>
                            <?= html($marca['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($errors['marca_id'])): ?>
                    <p class="error"><?= html($errors['marca_id']) ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label for="nombre">Nombre del producto:</label>
                <input type="text" id="nombre" name="nombre" placeholder="Nombre del perfume"
                    value="<?= html($old['nombre'] ?? '') ?>" required>
                <?php if (!empty($errors['nombre'])): ?>
                    <p class="error"><?= html($errors['nombre']) ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label for="notas_olfativas">Notas olfativas:</label>
                <textarea id="notas_olfativas" name="notas_olfativas" rows="3"
                    placeholder="Bergamota, vainilla, sándalo..."><?= html($old['notas_olfativas'] ?? '') ?></textarea>
            </div>

            <div>
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" rows="4"
                    placeholder="Familia olfativa, ocasión de uso, etc."><?= html($old['descripcion'] ?? '') ?></textarea>
            </div>

            <div>
                <label for="activo">
                    <input type="checkbox" id="activo" name="activo" value="1"
                        <?= !empty($old['activo']) ? 'checked' : '' ?>>
                    Producto activo (visible en el catálogo)
                </label>
            </div>

            <button type="submit">Guardar producto</button>
        </fieldset>
    </form>
</main>