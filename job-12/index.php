<?php
require_once 'Category.php';
require_once 'Product.php';
require_once 'Clothing.php';
require_once 'Electronic.php';

// Création d'une instance de la catégorie "Vêtements"
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

$clothingCreateResult = $clothing->create();

// Création d'une instance de la catégorie "Électronique"
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

$electronicCreateResult = $electronic->create();

// Récupération de tous les vêtements via findAll()
$clothingInstance = new Clothing(0, '', [], 0, '', 0, 0, '', '', '', 0);
$allClothings = $clothingInstance->findAll();

// Récupération de tous les produits électroniques via findAll()
$electronicInstance = new Electronic(0, '', [], 0, '', 0, 0, '', 0);
$allElectronics = $electronicInstance->findAll();

// Récupération d'un vêtement spécifique via findOneById()
$foundClothing = false;
if ($clothingCreateResult !== false) {
    $foundClothing = $clothingInstance->findOneById($clothing->getId());
}

// Récupération d'un produit électronique spécifique via findOneById()
$foundElectronic = false;
if ($electronicCreateResult !== false) {
    $foundElectronic = $electronicInstance->findOneById($electronic->getId());
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
    <!-- Récupération du vêtement via findOneById() -->
    <?php if ($clothingCreateResult !== false): ?>
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

    <!-- Récupération du produit électronique via findOneById() -->
    <?php if ($electronicCreateResult !== false): ?>
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

    <!-- Récupération de tous les vêtements via findAll() -->
    <div class="card">
        <h2>Tous les vêtements (via Clothing::findAll())</h2>
        <?php if (empty($allClothings)): ?>
            <p>Aucun vêtement trouvé.</p>
        <?php else: ?>
            <p><strong><?= count($allClothings) ?></strong> vêtement(s) trouvé(s)</p>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Taille</th>
                        <th>Couleur</th>
                        <th>Type</th>
                        <th>Frais matériau</th>
                        <th>Prix</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allClothings as $cloth): ?>
                        <tr>
                            <td><?= $cloth->getId() ?></td>
                            <td><?= htmlspecialchars($cloth->getName()) ?></td>
                            <td><?= htmlspecialchars($cloth->getSize()) ?></td>
                            <td><?= htmlspecialchars($cloth->getColor()) ?></td>
                            <td><?= htmlspecialchars($cloth->getType()) ?></td>
                            <td class="price"><?= number_format($cloth->getMaterialFee() / 100, 2, ',', ' ') ?> €</td>
                            <td class="price"><?= number_format($cloth->getPrice() / 100, 2, ',', ' ') ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Récupération de tous les produits électroniques via findAll() -->
    <div class="card">
        <h2>Tous les produits électroniques (via Electronic::findAll())</h2>
        <?php if (empty($allElectronics)): ?>
            <p>Aucun produit électronique trouvé.</p>
        <?php else: ?>
            <p><strong><?= count($allElectronics) ?></strong> produit(s) électronique(s) trouvé(s)</p>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Marque</th>
                        <th>Frais garantie</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allElectronics as $elec): ?>
                        <tr>
                            <td><?= $elec->getId() ?></td>
                            <td><?= htmlspecialchars($elec->getName()) ?></td>
                            <td><?= htmlspecialchars($elec->getBrand()) ?></td>
                            <td class="price"><?= number_format($elec->getWarrantyFee() / 100, 2, ',', ' ') ?> €</td>
                            <td class="price"><?= number_format($elec->getPrice() / 100, 2, ',', ' ') ?> €</td>
                            <td><?= $elec->getQuantity() ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Récupération d'un vêtement spécifique via findOneById() -->
    <?php if ($foundClothing !== false): ?>
        <div class="card" style="background-color: #e7f3ff; border-left: 4px solid #007bff;">
            <h2>Vêtement trouvé via findOneById(<?= $clothing->getId() ?>)</h2>
            <div class="info-line">
                <span class="label">ID :</span> <?= $foundClothing->getId() ?>
            </div>
            <div class="info-line">
                <span class="label">Nom :</span> <?= htmlspecialchars($foundClothing->getName()) ?>
            </div>
            <div class="info-line">
                <span class="label">Taille :</span> <?= htmlspecialchars($foundClothing->getSize()) ?>
            </div>
            <div class="info-line">
                <span class="label">Couleur :</span> <?= htmlspecialchars($foundClothing->getColor()) ?>
            </div>
            <div class="info-line">
                <span class="label">Type :</span> <?= htmlspecialchars($foundClothing->getType()) ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Récupération d'un produit électronique spécifique via findOneById() -->
    <?php if ($foundElectronic !== false): ?>
        <div class="card" style="background-color: #e7f3ff; border-left: 4px solid #007bff;">
            <h2>Produit électronique trouvé via findOneById(<?= $electronic->getId() ?>)</h2>
            <div class="info-line">
                <span class="label">ID :</span> <?= $foundElectronic->getId() ?>
            </div>
            <div class="info-line">
                <span class="label">Nom :</span> <?= htmlspecialchars($foundElectronic->getName()) ?>
            </div>
            <div class="info-line">
                <span class="label">Marque :</span> <?= htmlspecialchars($foundElectronic->getBrand()) ?>
            </div>
            <div class="info-line">
                <span class="label">Frais de garantie :</span>
                <span class="price"><?= number_format($foundElectronic->getWarrantyFee() / 100, 2, ',', ' ') ?> €</span>
            </div>
        </div>
    <?php endif; ?>
</body>

</html>