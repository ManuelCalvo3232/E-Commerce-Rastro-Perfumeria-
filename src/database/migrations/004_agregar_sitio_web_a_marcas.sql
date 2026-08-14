ALTER TABLE marcas
    ADD COLUMN sitio_web VARCHAR(255) NOT NULL DEFAULT 'https://www.rastroperfumeria.com' AFTER pais_origen;