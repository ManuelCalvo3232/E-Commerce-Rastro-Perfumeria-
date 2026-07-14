<?php

use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

// Cargar variables de entorno desde el .env
Dotenv::createImmutable(__DIR__ . '/..')->safeLoad();

$env = $_ENV["APP_ENV"] ?? "prod";
$allowedEnvs = ["dev", "prod"];

if (!in_array($env, $allowedEnvs, true)) {
  throw new RuntimeException("APP_ENV inválido: $env");
}

$debug = $env === "dev";

// Crear la aplicacion de Slim
$app = AppFactory::create();

// Crear el motor de plantillas
$renderer = new PhpRenderer(
  templatePath: __DIR__ . "/views",
  attributes: ["title" => "PDI | Slim Template 2026"],
);

// Ruta/Vista principal
$app->get("/", function ($request, $response) use ($renderer) {
  $productos = [
    ["nombre" => "Bleu de Chanel", "marca" => "Chanel", "precio" => 4500, "ml" => 5, "imagen" => "/img/bleu-chanel.jpg"],
    ["nombre" => "Sauvage", "marca" => "Dior", "precio" => 4200, "ml" => 5, "imagen" => "/img/sauvage.jpg"],
    ["nombre" => "Aventus", "marca" => "Creed", "precio" => 8900, "ml" => 5, "imagen" => "/img/aventus.jpg"],
  ];

  return view($renderer, $response, "index.php", ["productos" => $productos]);
});

// Ruta/Productos (listado)
$app->get("/productos", function ($request, $response) use ($renderer) {
  return view($renderer, $response, "productos/index.php", [
    "title" => "Listado de Productos",
  ]);
});

//.
$app->get("/productos/{id}", function ($request, $response, $args) use ($renderer) {
  return view($renderer, $response, "productos/show.php", [
    "title" => "Detalle de la Entidad",
    "id" => $args["id"],
  ]);
});

//.
$app->get("/create/productos", function ($request, $response) use ($renderer) {
  return view($renderer, $response, "productos/store.php", [
    "title" => "Creando Productos",
  ]);
});

//. 
$app->post("/productos", function ($request, $response) use ($renderer) {
  $data = $request->getParsedBody();

  $nombre       = trim($data["nombre-perfume"] ?? "");
  $marca        = trim($data["marca"] ?? "");
  $categoria    = trim($data["categoria"] ?? "");
  $precioEntero = trim($data["precio-entero"] ?? "");
  $precio5ml    = trim($data["precio-5ml"] ?? "");
  $precio10ml   = trim($data["precio-10ml"] ?? "");
  $descripcion  = trim($data["descripcion"] ?? "");

  if ($nombre === "" || $marca === "" || $categoria === "" || $precioEntero === "" || $precio5ml === "" || $precio10ml === "") {
    return view($renderer, $response, "productos/error.php", [
      "title" => "Error al crear producto",
    ])->withStatus(422);
  }

  return view($renderer, $response, "productos/created.php", [
    "title" => "Producto creado",
    "producto" => [
      "nombre" => $nombre,
      "marca" => $marca,
      "categoria" => $categoria,
      "precioEntero" => $precioEntero,
      "precio5ml" => $precio5ml,
      "precio10ml" => $precio10ml,
      "descripcion" => $descripcion,
    ],
  ]);
});

$app->addErrorMiddleware($debug, true, true);

return $app;
