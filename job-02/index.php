<?php
require_once 'Category.php';
require_once 'Product.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produit - <?= $product->getName() ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        h2 {
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }

        .info-line {
            margin: 15px 0;
            font-size: 16px;
        }

        .label {
            font-weight: bold;
            color: #555;
        }

        .price {
            color: #28a745;
            font-size: 24px;
            font-weight: bold;
        }

        .modified {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin-top: 10px;
        }

        .category-badge {
            background-color: #007bff;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            display: inline-block;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="card">
        <h2>Catégorie</h2>
        <div class="info-line">
            <span class="category-badge"><?= $category->getName() ?></span>
        </div>
        <div class="info-line">
            <span class="label">Description :</span> <?= $category->getDescription() ?>
        </div>
    </div>

    <div class="card">
        <h2>Informations du produit</h2>
        <div class="info-line">
            <span class="label">Produit :</span> <?= $product->getName() ?>
        </div>
        <div class="info-line">
            <span class="label">Prix :</span>
            <span class="price"><?= number_format($product->getPrice() / 100, 2, ',', ' ') ?> €</span>
        </div>
        <div class="info-line">
            <span class="label">Quantité :</span> <?= $product->getQuantity() ?> en stock
        </div>
        <div class="info-line">
            <span class="label">Description :</span> <?= $product->getDescription() ?>
        </div>
        <div class="info-line">
            <span class="label">Photos :</span> <?= implode(', ', $product->getPhotos()) ?>
        </div>
        <div class="info-line">
            <span class="label">ID de catégorie :</span> <?= $product->getCategoryId() ?>
        </div>
        <div class="info-line">
            <span class="label">Créé le :</span> <?= $product->getCreatedAt()->format('d/m/Y à H:i:s') ?>
        </div>
    </div>

    <?php
    // Modification du produit
    $product->setCategoryId(2);
    ?>

    <div class="card">
        <h2>Après modification</h2>
        <div class="modified">
            <div class="info-line">
                <span class="label">Nouvelle catégorie ID :</span> <?= $product->getCategoryId() ?>
            </div>
            <div class="info-line">
                <span class="label">Mis à jour le :</span> <?= $product->getUpdatedAt()->format('d/m/Y à H:i:s') ?>
            </div>
        </div>
    </div>
</body>

</html>