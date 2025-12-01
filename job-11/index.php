<?php
require_once 'Category.php';
require_once 'Product.php';
require_once 'Clothing.php';
require_once 'Electronic.php';

// Création d'un vêtement
$clothing = new Clothing(
    0, // ID auto-généré
    "T-shirt Coton Bio",
    ["tshirt1.jpg", "tshirt2.jpg"],
    2990, // 29,90 €
    "T-shirt en coton biologique, confortable et écologique",
    100,
    2, // Catégorie vêtements
    "L", // Taille
    "Bleu", // Couleur
    "T-shirt", // Type
    500 // Frais de matériau en centimes
);

$clothingResult = $clothing->create();

// Création d'un produit électronique
$electronic = new Electronic(
    0, // ID auto-généré
    "Smartphone Galaxy Pro",
    ["smartphone1.jpg", "smartphone2.jpg"],
    79900, // 799,00 €
    "Smartphone haut de gamme avec écran AMOLED",
    50,
    1, // Catégorie électronique
    "Samsung", // Marque
    12000 // Frais de garantie en centimes (120,00 €)
);

$electronicResult = $electronic->create();

// Récupération de tous les produits pour affichage
$productInstance = new Product(0, '', [], 0, '', 0, 0);
$allProducts = $productInstance->findAll();
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
            font-size: 18px;
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
    <!-- Résultat de la création du vêtement -->
    <?php if ($clothingResult !== false): ?>
        <div class="card" style="background-color: #d4edda; border-left: 4px solid #28a745;">
            <h2>Vêtement créé avec succès !</h2>
            <div class="info-line">
                <span class="label">ID :</span> <?= $clothing->getId() ?>
            </div>
            <div class="info-line">
                <span class="label">Nom :</span> <?= htmlspecialchars($clothing->getName()) ?>
            </div>
            <div class="info-line">
                <span class="label">Taille :</span> <?= htmlspecialchars($clothing->getSize()) ?>
            </div>
            <div class="info-line">
                <span class="label">Couleur :</span> <?= htmlspecialchars($clothing->getColor()) ?>
            </div>
            <div class="info-line">
                <span class="label">Type :</span> <?= htmlspecialchars($clothing->getType()) ?>
            </div>
            <div class="info-line">
                <span class="label">Frais de matériau :</span>
                <span class="price"><?= number_format($clothing->getMaterialFee() / 100, 2, ',', ' ') ?> €</span>
            </div>
            <div class="info-line">
                <span class="label">Prix :</span>
                <span class="price"><?= number_format($clothing->getPrice() / 100, 2, ',', ' ') ?> €</span>
            </div>
        </div>
    <?php else: ?>
        <div class="card" style="background-color: #f8d7da; border-left: 4px solid #dc3545;">
            <h2>❌ Erreur lors de la création du vêtement</h2>
        </div>
    <?php endif; ?>

    <!-- Résultat de la création du produit électronique -->
    <?php if ($electronicResult !== false): ?>
        <div class="card" style="background-color: #d4edda; border-left: 4px solid #28a745;">
            <h2>Produit électronique créé avec succès !</h2>
            <div class="info-line">
                <span class="label">ID :</span> <?= $electronic->getId() ?>
            </div>
            <div class="info-line">
                <span class="label">Nom :</span> <?= htmlspecialchars($electronic->getName()) ?>
            </div>
            <div class="info-line">
                <span class="label">Marque :</span> <?= htmlspecialchars($electronic->getBrand()) ?>
            </div>
            <div class="info-line">
                <span class="label">Frais de garantie :</span>
                <span class="price"><?= number_format($electronic->getWarrantyFee() / 100, 2, ',', ' ') ?> €</span>
            </div>
            <div class="info-line">
                <span class="label">Prix :</span>
                <span class="price"><?= number_format($electronic->getPrice() / 100, 2, ',', ' ') ?> €</span>
            </div>
        </div>
    <?php else: ?>
        <div class="card" style="background-color: #f8d7da; border-left: 4px solid #dc3545;">
            <h2>❌ Erreur lors de la création du produit électronique</h2>
        </div>
    <?php endif; ?>

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
                        <th>Mis à jour le</th>
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
                            <td><?= $prod->getUpdatedAt()->format('d/m/Y H:i:s') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>