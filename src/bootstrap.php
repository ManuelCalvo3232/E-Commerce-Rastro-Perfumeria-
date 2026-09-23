<?php

use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/database/database.php';

// Cargar variables de entorno desde el .env
Dotenv::createImmutable(__DIR__ . '/..')->safeLoad();

$env = $_ENV["APP_ENV"] ?? "prod";
$allowedEnvs = ["dev", "prod"];

if (!in_array($env, $allowedEnvs, true)) {
  throw new RuntimeException("APP_ENV inválido: $env");
}

$debug = $env === "dev";

$app = AppFactory::create();

$renderer = new PhpRenderer(
  templatePath: __DIR__ . "/views",
  attributes: ["title" => "PDI | Slim Template 2026"],
);

$database = new Database();


// Ruta/Vista principal 
$app->get("/", function ($request, $response) use ($renderer) {
  $productos = [
    ["nombre" => "Bleu de Chanel", "marca" => "Chanel", "precio" => 4500, "ml" => 5, "imagen" => "/img/bleu-chanel.jpg"],
    ["nombre" => "Sauvage", "marca" => "Dior", "precio" => 4200, "ml" => 5, "imagen" => "/img/sauvage.jpg"],
    ["nombre" => "Aventus", "marca" => "Creed", "precio" => 8900, "ml" => 5, "imagen" => "/img/aventus.jpg"],
  ];

  return view($renderer, $response, "index.php", ["productos" => $productos]);
});

// Helper: trae las marcas para poblar el <select> de los formularios
$fetchMarcas = function () use ($database) {
  return $database->getConnection()
    ->query("SELECT id, nombre FROM marcas ORDER BY nombre ASC")
    ->fetchAll();
};

// Helper: valida los datos de un producto recibidos por formulario
$validateProducto = function (array $data): array {
  $errors = [];

  $marcaId = trim((string) ($data["marca_id"] ?? ""));
  if ($marcaId === "" || !ctype_digit($marcaId)) {
    $errors["marca_id"] = "Seleccioná una marca válida.";
  }

  $nombre = trim((string) ($data["nombre"] ?? ""));
  if ($nombre === "") {
    $errors["nombre"] = "El nombre es obligatorio.";
  }

  return $errors;
};

$app->group("/productos", function ($group) use ($renderer, $database, $fetchMarcas, $validateProducto) {

  // GET /productos/ -> listado
  $group->get("/", function ($request, $response) use ($renderer, $database) {
    $productos = $database->getConnection()->query(
      "SELECT productos.*, marcas.nombre AS marca_nombre
       FROM productos
       INNER JOIN marcas ON marcas.id = productos.marca_id
       ORDER BY productos.creado_en DESC"
    )->fetchAll();

    return view($renderer, $response, "productos/index.php", [
      "title" => "Listado de Productos",
      "productos" => $productos,
    ]);
  });

  // GET /productos/create -> formulario de creación
  $group->get("/create", function ($request, $response) use ($renderer, $fetchMarcas) {
    return view($renderer, $response, "productos/create.php", [
      "title" => "Nuevo producto",
      "marcas" => $fetchMarcas(),
      "old" => [],
      "errors" => [],
    ]);
  });

  // GET /productos/update/{id} -> formulario de edición
  $group->get("/update/{id}", function ($request, $response, $args) use ($renderer, $database, $fetchMarcas) {
    $id = (int) $args["id"];

    $query = $database->getConnection()->prepare("SELECT * FROM productos WHERE id = ?");
    $query->execute([$id]);
    $producto = $query->fetch();

    if ($producto === false) {
      return view($renderer, $response, "productos/not_found.php", [
        "title" => "Producto no encontrado",
        "id" => $id,
      ])->withStatus(404);
    }

    return view($renderer, $response, "productos/update.php", [
      "title" => "Editar producto",
      "producto" => $producto,
      "marcas" => $fetchMarcas(),
      "errors" => [],
    ]);
  });

  // GET /productos/{id} -> detalle
  $group->get("/{id}", function ($request, $response, $args) use ($renderer, $database) {
    $id = (int) $args["id"];

    $query = $database->getConnection()->prepare(
      "SELECT productos.*, marcas.nombre AS marca_nombre
       FROM productos
       INNER JOIN marcas ON marcas.id = productos.marca_id
       WHERE productos.id = ?"
    );
    $query->execute([$id]);
    $producto = $query->fetch();

    if ($producto === false) {
      return view($renderer, $response, "productos/not_found.php", [
        "title" => "Producto no encontrado",
        "id" => $id,
      ])->withStatus(404);
    }

    return view($renderer, $response, "productos/show.php", [
      "title" => "Detalle del producto",
      "producto" => $producto,
    ]);
  });

  // POST /productos -> crea
  $group->post("", function ($request, $response) use ($renderer, $database, $fetchMarcas, $validateProducto) {
    $data = $request->getParsedBody() ?? [];
    $errors = $validateProducto($data);

    if (!empty($errors)) {
      return view($renderer, $response, "productos/create.php", [
        "title" => "Nuevo producto",
        "marcas" => $fetchMarcas(),
        "old" => $data,
        "errors" => $errors,
      ])->withStatus(422);
    }

    $descripcion = trim((string) ($data["descripcion"] ?? ""));
    $notas = trim((string) ($data["notas_olfativas"] ?? ""));

    $id = $database->runTransaction(function ($pdo) use ($data, $descripcion, $notas) {
      $query = $pdo->prepare(
        "INSERT INTO productos (marca_id, nombre, descripcion, notas_olfativas, activo)
         VALUES (?, ?, ?, ?, ?)"
      );
      $query->execute([
        (int) $data["marca_id"],
        trim($data["nombre"]),
        $descripcion !== "" ? $descripcion : null,
        $notas !== "" ? $notas : null,
        isset($data["activo"]) ? 1 : 0,
      ]);

      return (int) $pdo->lastInsertId();
    });

    return $response->withHeader("Location", "/productos/{$id}")->withStatus(302);
  });

  // PUT /productos/{id} -> actualiza
  $group->put("/{id}", function ($request, $response, $args) use ($renderer, $database, $fetchMarcas, $validateProducto) {
    $id = (int) $args["id"];
    $pdo = $database->getConnection();

    $check = $pdo->prepare("SELECT id FROM productos WHERE id = ?");
    $check->execute([$id]);
    if ($check->fetch() === false) {
      return view($renderer, $response, "productos/not_found.php", [
        "title" => "Producto no encontrado",
        "id" => $id,
      ])->withStatus(404);
    }

    $data = $request->getParsedBody() ?? [];
    $errors = $validateProducto($data);

    if (!empty($errors)) {
      return view($renderer, $response, "productos/update.php", [
        "title" => "Editar producto",
        "producto" => ["id" => $id] + $data,
        "marcas" => $fetchMarcas(),
        "errors" => $errors,
      ])->withStatus(422);
    }

    $descripcion = trim((string) ($data["descripcion"] ?? ""));
    $notas = trim((string) ($data["notas_olfativas"] ?? ""));

    $database->runTransaction(function ($pdo) use ($id, $data, $descripcion, $notas) {
      $query = $pdo->prepare(
        "UPDATE productos
         SET marca_id = ?, nombre = ?, descripcion = ?, notas_olfativas = ?, activo = ?
         WHERE id = ?"
      );
      $query->execute([
        (int) $data["marca_id"],
        trim($data["nombre"]),
        $descripcion !== "" ? $descripcion : null,
        $notas !== "" ? $notas : null,
        isset($data["activo"]) ? 1 : 0,
        $id,
      ]);
    });

    return $response->withHeader("Location", "/productos/{$id}")->withStatus(302);
  });

  // DELETE /productos/{id} -> elimina
  $group->delete("/{id}", function ($request, $response, $args) use ($renderer, $database) {
    $id = (int) $args["id"];
    $pdo = $database->getConnection();

    $check = $pdo->prepare("SELECT id FROM productos WHERE id = ?");
    $check->execute([$id]);
    if ($check->fetch() === false) {
      return view($renderer, $response, "productos/not_found.php", [
        "title" => "Producto no encontrado",
        "id" => $id,
      ])->withStatus(404);
    }

    $database->runTransaction(function ($pdo) use ($id) {
      $query = $pdo->prepare("DELETE FROM productos WHERE id = ?");
      $query->execute([$id]);
    });

    return $response->withHeader("Location", "/productos/")->withStatus(302);
  });
});

$app->addErrorMiddleware($debug, true, true);

return $app;