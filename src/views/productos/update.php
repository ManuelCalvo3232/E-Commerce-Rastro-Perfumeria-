<main class="form-container">
    <h1>Editar producto</h1>

    <form id="update-producto-form" class="form">
        <fieldset>
            <legend>Información del producto</legend>

            <div>
                <label for="marca_id">Marca:</label>
                <select id="marca_id" name="marca_id" required>
                    <option value="">Seleccionar...</option>
                    <?php foreach ($marcas as $marca): ?>
                        <option value="<?= html((string) $marca['id']) ?>"
                            <?= ($producto['marca_id'] == $marca['id']) ? 'selected' : '' ?>>
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
                <input type="text" id="nombre" name="nombre"
                    value="<?= html($producto['nombre'] ?? '') ?>" required>
                <?php if (!empty($errors['nombre'])): ?>
                    <p class="error"><?= html($errors['nombre']) ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label for="notas_olfativas">Notas olfativas:</label>
                <textarea id="notas_olfativas" name="notas_olfativas" rows="3"><?= html($producto['notas_olfativas'] ?? '') ?></textarea>
            </div>

            <div>
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" rows="4"><?= html($producto['descripcion'] ?? '') ?></textarea>
            </div>

            <div>
                <label for="activo">
                    <input type="checkbox" id="activo" name="activo" value="1"
                        <?= !empty($producto['activo']) ? 'checked' : '' ?>>
                    Producto activo (visible en el catálogo)
                </label>
            </div>

            <button type="submit">Guardar cambios</button>
        </fieldset>
    </form>

    <script>
        document.getElementById('update-producto-form').addEventListener('submit', async function (event) {
            event.preventDefault();

            const formData = new FormData(event.target);
            const params = new URLSearchParams();
            for (const [key, value] of formData.entries()) {
                params.append(key, value);
            }

            const response = await fetch('/productos/<?= html((string) $producto['id']) ?>', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: params.toString(),
            });

            if (response.redirected) {
                window.location.href = response.url;
            } else {
                document.open();
                document.write(await response.text());
                document.close();
            }
        });
    </script>
</main>