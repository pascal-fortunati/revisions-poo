<?php
require_once 'vendor/autoload.php';

use App\Clothing;
use App\Electronic;
use App\Category;
use App\EntityCollection;

// Création d'une collection manuelle de produits
$collection = new EntityCollection();

// Ajout de quelques produits
$product1 = new Clothing(1, "T-shirt", [], 2990, "Un t-shirt", 10, 2, "L", "Bleu", "T-shirt", 500);
$product2 = new Electronic(2, "Smartphone", [], 79900, "Un smartphone", 5, 1, "Samsung", 12000);

$collection->add($product1);
$collection->add($product2);

$p1 = $collection->get(0);
$p2 = $collection->get(1);

// méthode remove()
$collectionBeforeRemove = $collection->count();
$collection->remove($product1);
$collectionAfterRemove = $collection->count();

// Récupération des produits d'une catégorie via retrieve()
$category = new Category(2, "Vêtements", "Catégorie des vêtements");
$productsCollection = $category->getProducts();

// Récupération de tous les vêtements existants
$clothingInstance = new Clothing(0, '', [], 0, '', 0, 0, '', '', '', 0);
$allClothings = $clothingInstance->findAll();

// Détermination de l'action (création ou mise à jour)
$clothingAction = 'créé'; // Par défaut
if (!empty($allClothings)) {
    // On prend le dernier de la liste
    $clothing = end($allClothings);
    echo "<!-- Vêtement existant trouvé (ID: " . $clothing->getId() . "), modification du prix -->";

    // On modifie le prix
    $clothing->setPrice($clothing->getPrice() + 100); // Augmente le prix de 1€
    $clothingCreateResult = $clothing->save(); // Mise à jour via save()
    $clothingAction = 'mis à jour';
} else {
    // Aucun vêtement existant, on en crée un nouveau
    echo "<!-- Aucun vêtement existant, création d'un nouveau -->";
    $clothing = new Clothing(
        0, // ID auto-généré
        "T-shirt Coton Bio",
        ["tshirt1.jpg", "tshirt2.jpg"],
        2990,
        "T-shirt en coton biologique, confortable et écologique",
        100,
        2,
        "L",
        "Bleu",
        "T-shirt",
        500
    );

    $clothingCreateResult = $clothing->save(); // Création via save()
}

// Récupération de tous les produits électroniques existants
$electronicInstance = new Electronic(0, '', [], 0, '', 0, 0, '', 0);
$allElectronics = $electronicInstance->findAll();

$electronicAction = 'créé'; // Par défaut
if (!empty($allElectronics)) {
    $electronic = end($allElectronics);
    echo "<!-- Électronique existant trouvé (ID: " . $electronic->getId() . "), modification du stock -->";

    // Modification du stock
    $electronic->removeStocks(5);
    $electronicCreateResult = $electronic->save(); // save() fera un UPDATE
    $electronicAction = 'mis à jour';
} else {
    echo "<!-- Aucun électronique existant, création d'un nouveau -->";
    $electronic = new Electronic(
        0,
        "Smartphone Galaxy Pro",
        ["smartphone1.jpg", "smartphone2.jpg"],
        79900,
        "Smartphone haut de gamme avec écran AMOLED",
        50,
        1,
        "Samsung",
        12000
    );

    $electronicCreateResult = $electronic->save(); // save() fera un CREATE
}

// Rafraîchir la liste après les modifications
$allClothings = $clothingInstance->findAll();
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
    <!-- Démonstration EntityCollection -->
    <div class="card" style="background-color: #e7f3ff; border-left: 4px solid #007bff;">
        <h2>Création manuelle d'une EntityCollection</h2>
        <div class="info-line">
            <span class="label">Nombre de produits ajoutés :</span> <?= $collectionBeforeRemove ?>
        </div>
        <?php if ($p1 instanceof Clothing || $p1 instanceof Electronic): ?>
            <div class="info-line">
                <span class="label">Produit 1 :</span> <?= htmlspecialchars($p1->getName()) ?>
            </div>
        <?php endif; ?>
        <?php if ($p2 instanceof Clothing || $p2 instanceof Electronic): ?>
            <div class="info-line">
                <span class="label">Produit 2 :</span> <?= htmlspecialchars($p2->getName()) ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="card" style="background-color: #fff3cd; border-left: 4px solid #ffc107;">
        <h2>Suppression d'un produit avec remove()</h2>
        <div class="info-line">
            <span class="label">Avant suppression :</span> <?= $collectionBeforeRemove ?> produits
        </div>
        <div class="info-line">
            <span class="label">Après suppression :</span> <?= $collectionAfterRemove ?> produit
        </div>
    </div>

    <div class="card" style="background-color: #d4edda; border-left: 4px solid #28a745;">
        <h2>Récupération des produits via retrieve()</h2>
        <div class="info-line">
            <span class="label">Catégorie :</span> <?= htmlspecialchars($category->getName()) ?>
        </div>
        <div class="info-line">
            <span class="label">Nombre de produits trouvés :</span> <?= $productsCollection->count() ?>
        </div>

        <?php if (!$productsCollection->isEmpty()): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Type</th>
                        <th>Prix</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productsCollection->getAll() as $product): ?>
                        <?php if ($product instanceof Clothing || $product instanceof Electronic): ?>
                            <tr>
                                <td><?= $product->getId() ?></td>
                                <td><?= htmlspecialchars($product->getName()) ?></td>
                                <td><?= $product instanceof Clothing ? 'Vêtement' : 'Électronique' ?></td>
                                <td class="price"><?= number_format($product->getPrice() / 100, 2, ',', ' ') ?> €</td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="color: #856404;">Aucun produit trouvé dans cette catégorie.</p>
        <?php endif; ?>
    </div>

    <!-- Récupération du vêtement via findOneById() -->
    <?php if ($clothingCreateResult !== false): ?>
        <div class="card" style="background-color: #d4edda; border-left: 4px solid #28a745;">
            <h2>Vêtement <?= $clothingAction ?> avec succès !</h2>
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
            <div class="info-line" style="background-color: #fff3cd; padding: 10px; border-radius: 5px;">
                <span class="label">Quantité en stock (après +50) :</span>
                <strong><?= $clothing->getQuantity() ?></strong>
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
            <h2>Produit électronique <?= $electronicAction ?> avec succès !</h2>
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
            <div class="info-line" style="background-color: #fff3cd; padding: 10px; border-radius: 5px;">
                <span class="label">Quantité en stock (après -20) :</span>
                <strong><?= $electronic->getQuantity() ?></strong>
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