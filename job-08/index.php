<?php
require_once 'Category.php';
require_once 'Product.php';

// Test de la méthode findAll()
$productInstance = new Product(0, '', [], 0, '', 0, 0);
$allProducts = $productInstance->findAll();

// Test de findOneById pour afficher un produit spécifique
$product = new Product(0, '', [], 0, '', 0, 0);
$result = $product->findOneById(7);

if ($result === false) {
    $product = null;
} else {
    $category = $product->getCategory();
}
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
            max-width: 1600px;
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
            font-size: 20px;
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
        <h2>Tous les produits (via findAll())</h2>
        <?php if (empty($allProducts)): ?>
            <p>Aucun produit trouvé.</p>
        <?php else: ?>
            <p><strong><?= count($allProducts) ?></strong> produit(s) trouvé(s) dans la base de données</p>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Description</th>
                        <th>Catégorie ID</th>
                        <th>Créé le</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allProducts as $prod): ?>
                        <tr>
                            <td><?= $prod->getId() ?></td>
                            <td><?= htmlspecialchars($prod->getName()) ?></td>
                            <td class="price"><?= number_format($prod->getPrice() / 100, 2, ',', ' ') ?> €</td>
                            <td><?= $prod->getQuantity() ?></td>
                            <td><?= htmlspecialchars($prod->getDescription()) ?></td>
                            <td><?= $prod->getCategoryId() ?></td>
                            <td><?= $prod->getCreatedAt()->format('d/m/Y H:i') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <?php if ($product): ?>
        <div class="card">
            <h2>Détail du produit ID 7 (via findOneById())</h2>
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
        </div>

        <?php if (isset($category) && $category): ?>
            <div class="card">
                <h2>📁 Catégorie associée</h2>
                <div class="info-line">
                    <span class="label">Nom :</span>
                    <span class="category-badge"><?= htmlspecialchars($category->getName()) ?></span>
                </div>
                <div class="info-line">
                    <span class="label">Description :</span> <?= htmlspecialchars($category->getDescription()) ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</body>

</html>