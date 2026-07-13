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
      ["nombre" => "Bleu de Chanel", "marca" => "Chanel", "precio100ml" => 100000, "precio10ml" => 10000, "precio5ml" => 4500, "10ml" => 10, "5ml" => 5, "imagen" => "/img/bleu-chanel.jpg"],
      ["nombre" => "Sauvage", "marca" => "Dior", "precio100ml" => 4200, "precio10ml" => 420, "precio5ml" => 210, "10ml" => 10, "5ml" => 5, "imagen" => "/img/sauvage.jpg"],
      ["nombre" => "Aventus", "marca" => "Creed", "precio100ml" => 8900, "precio10ml" => 890, "precio5ml" => 445, "10ml" => 10, "5ml" => 5, "imagen" => "/img/aventus.jpg"],
    ],
    "Femenino" => [
      ["nombre" => "Good Girl", "marca" => "Carolina Herrera", "precio100ml" => 4300, "precio10ml" => 430, "precio5ml" => 215, "10ml" => 10, "5ml" => 5, "imagen" => "/img/good-girl.jpg"],
      ["nombre" => "Black Opium", "marca" => "YSL", "precio100ml" => 4100, "precio10ml" => 410, "precio5ml" => 205, "10ml" => 10, "5ml" => 5, "imagen" => "/img/black-opium.jpg"],
    ],
    "Nicho" => [
      ["nombre" => "Baccarat Rouge 540", "marca" => "Maison Francis Kurkdjian", "precio100ml" => 9800, "precio10ml" => 980, "precio5ml" => 490, "10ml" => 10, "5ml" => 5, "imagen" => "/img/baccarat-540.jpg"],
    ],
  ];

  return view($renderer, $response, "productos.php", [
    "title" => "Catálogo | Rastro Perfumería",
    "catalogo" => $catalogo,
  ]);
});

$app->addErrorMiddleware($debug, true, true);

return $app;
