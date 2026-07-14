<main class="form-container">
    <h1>Creando Producto</h1>

    <form action="/productos" method="post">
        <fieldset>
            <legend>Información del producto</legend>

            <div>
                <label for="nombre-perfume">Nombre del perfume:</label>
                <input type="text" id="nombre-perfume" name="nombre-perfume" placeholder="Nombre del perfume" required>
            </div>

            <div>
                <label for="marca">Marca del perfume: </label>
                <input type="text" id="marca" name="marca" placeholder="Marca de perfume" required>
            </div>

            <div>
                <label for="categoria">Categoría</label>
                <select id="categoria" name="categoria" required>
                    <option value="">Seleccionar...</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                    <option value="Nicho">Nicho</option>
                </select>
            </div>

            <div>
                <label for="precio-entero"> Precio 100ml:</label>
                <input type="number" id="precio-entero" name="precio-entero" placeholder="Precio del perfume entero" required>
            </div>

            <div>
                <label for="precio-5ml"> Precio 5ml:</label>
                <input type="number" id="precio-5ml" name="precio-5ml" placeholder="Precio del decant de 5ml" required>
            </div>

            <div>
                <label for="precio-10ml"> Precio 10ml:</label>
                <input type="number" id="precio-10ml" name="precio-10ml" placeholder="Precio del decant de 10ml" required>
            </div>

            <div>
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="4" placeholder="Notas principales, familia olfativa, etc."></textarea>
            </div>

            <button type="submit"> Agregar producto </button>
        </fieldset>
    </form>
</main>
