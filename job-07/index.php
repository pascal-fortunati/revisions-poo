<?php
require_once 'Category.php';
require_once 'Product.php';

// Création d'une instance de Product
$product = new Product(0, '', [], 0, '', 0, 0);

// Recherche du produit avec l'id 7
$result = $product->findOneById(7);

if ($result === false) {
    die("Le produit avec l'id 7 n'existe pas dans la base de données.");
}

// Récupération de la catégorie associée au produit
$category = $product->getCategory();

// Récupération de tous les produits de cette catégorie
$productsInCategory = [];
if ($category) {
    $productsInCategory = $category->getProducts();
}

$productNotFound = new Product(0, '', [], 0, '', 0, 0);
$resultNotFound = $productNotFound->findOneById(99999);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Draft Shop - Catalogue</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1400px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>

<body>
    <div class="card">
        <h2>Produit ID 7</h2>
        <div class="info-line">
            <span class="label">ID :</span> <?= $product->getId() ?>
        </div>
        <div class="info-line">
            <span class="label">Nom :</span> <?= htmlspecialchars($product->getName()) ?>
        </div>
        <div class="info-line">
            <span class="label">Prix :</span>
            <span class="price"><?= number_format($product->getPrice() / 100, 2, ',', ' ') ?> €</span>
        </div>
        <div class="info-line">
            <span class="label">Quantité :</span> <?= $product->getQuantity() ?> en stock
        </div>
        <div class="info-line">
            <span class="label">Description :</span> <?= htmlspecialchars($product->getDescription()) ?>
        </div>
        <div class="info-line">
            <span class="label">Créé le :</span> <?= $product->getCreatedAt()->format('d/m/Y à H:i:s') ?>
        </div>
    </div>

    <?php if ($category): ?>
        <div class="card">
            <h2>Catégorie associée (via getCategory())</h2>
            <div class="info-line">
                <span class="label">ID :</span> <?= $category->getId() ?>
            </div>
            <div class="info-line">
                <span class="label">Nom :</span>
                <span class="category-badge"><?= htmlspecialchars($category->getName()) ?></span>
            </div>
            <div class="info-line">
                <span class="label">Description :</span> <?= htmlspecialchars($category->getDescription()) ?>
            </div>
            <div class="info-line">
                <span class="label">Créée le :</span> <?= $category->getCreatedAt()->format('d/m/Y à H:i:s') ?>
            </div>
        </div>

        <div class="card">
            <h2>Tous les produits de cette catégorie (via getProducts())</h2>
            <?php if (empty($productsInCategory)): ?>
                <p>Aucun produit dans cette catégorie.</p>
            <?php else: ?>
                <p><strong><?= count($productsInCategory) ?></strong> produit(s) trouvé(s)</p>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productsInCategory as $prod): ?>
                            <tr>
                                <td><?= $prod->getId() ?></td>
                                <td><?= htmlspecialchars($prod->getName()) ?></td>
                                <td class="price"><?= number_format($prod->getPrice() / 100, 2, ',', ' ') ?> €</td>
                                <td><?= $prod->getQuantity() ?></td>
                                <td><?= htmlspecialchars($prod->getDescription()) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</body>

</html>