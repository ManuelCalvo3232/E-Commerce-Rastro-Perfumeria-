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
  return view($renderer, $response, "index.php");
});

// Ruta/Productos
$app->get("/productos", function ($request, $response) use ($renderer) {
  $catalogo = [
    "Masculino" => [
      ["nombre" => "Bleu de Chanel", "marca" => "Chanel", "precio" => 4500, "ml" => 5, "imagen" => "/img/bleu-chanel.jpg"],
      ["nombre" => "Sauvage", "marca" => "Dior", "precio" => 4200, "ml" => 5, "imagen" => "/img/sauvage.jpg"],
      ["nombre" => "Aventus", "marca" => "Creed", "precio" => 8900, "ml" => 5, "imagen" => "/img/aventus.jpg"],
    ],
    "Femenino" => [
      ["nombre" => "Good Girl", "marca" => "Carolina Herrera", "precio" => 4300, "ml" => 5, "imagen" => "/img/good-girl.jpg"],
      ["nombre" => "Black Opium", "marca" => "YSL", "precio" => 4100, "ml" => 5, "imagen" => "/img/black-opium.jpg"],
    ],
    "Nicho" => [
      ["nombre" => "Baccarat Rouge 540", "marca" => "Maison Francis Kurkdjian", "precio" => 9800, "ml" => 5, "imagen" => "/img/baccarat-540.jpg"],
    ],
  ];

  return view($renderer, $response, "productos.php", [
    "title" => "Catálogo | Rastro Perfumería",
    "catalogo" => $catalogo,
  ]);
});

$app->addErrorMiddleware($debug, true, true);

return $app;
