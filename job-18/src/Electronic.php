<?php

namespace App;

use App\Abstract\AbstractProduct;
use App\Interface\StockableInterface;
use PDO;
use PDOException;

// Classe Electronic qui étend la classe AbstractProduct et implémente StockableInterface
class Electronic extends AbstractProduct implements StockableInterface
{
    private string $brand;
    private int $warranty_fee;

    // Constructeur
    public function __construct(
        int $id,
        string $name,
        array $photos,
        int $price,
        string $description,
        int $quantity,
        int $category_id,
        string $brand,
        int $warranty_fee
    ) {
        // Appel du constructeur parent
        parent::__construct($id, $name, $photos, $price, $description, $quantity, $category_id);

        $this->brand = $brand;
        $this->warranty_fee = $warranty_fee;
    }

    // Routeurs - Getters
    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getWarrantyFee(): int
    {
        return $this->warranty_fee;
    }

    // Routeurs - Setters
    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
    }

    public function setWarrantyFee(int $warranty_fee): void
    {
        $this->warranty_fee = $warranty_fee;
    }

    // Implémentation de la méthode abstraite findOneById
    public function findOneById(int $id): self|false
    {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8mb4', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Jointure entre product et electronic
            $stmt = $pdo->prepare("
                SELECT p.*, e.brand, e.warranty_fee
                FROM product p
                INNER JOIN electronic e ON p.id = e.product_id
                WHERE p.id = :id
            ");

            $stmt->execute(['id' => $id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                return false;
            }

            // Créer une instance de Electronic avec toutes les données
            return new Electronic(
                $data['id'],
                $data['name'],
                json_decode($data['photos'], true),
                $data['price'],
                $data['description'],
                $data['quantity'],
                $data['category_id'],
                $data['brand'],
                $data['warranty_fee']
            );
        } catch (PDOException $e) {
            return false;
        }
    }

    // Implémentation de la méthode abstraite findAll
    public function findAll(): array
    {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8mb4', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Jointure entre product et electronic
            $stmt = $pdo->query("
                SELECT p.*, e.brand, e.warranty_fee
                FROM product p
                INNER JOIN electronic e ON p.id = e.product_id
            ");

            $products = [];
            while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $products[] = new Electronic(
                    $data['id'],
                    $data['name'],
                    json_decode($data['photos'], true),
                    $data['price'],
                    $data['description'],
                    $data['quantity'],
                    $data['category_id'],
                    $data['brand'],
                    $data['warranty_fee']
                );
            }

            return $products;
        } catch (PDOException $e) {
            return [];
        }
    }

    // Implémentation de la méthode abstraite create
    public function create(): self|false
    {
        // Créer d'abord le produit de base
        if (!$this->createBaseProduct()) {
            return false;
        }

        try {
            $pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8mb4', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("
                INSERT INTO electronic (product_id, brand, warranty_fee)
                VALUES (:product_id, :brand, :warranty_fee)
            ");

            $stmt->execute([
                'product_id' => $this->getId(),
                'brand' => $this->brand,
                'warranty_fee' => $this->warranty_fee
            ]);

            return $this;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Implémentation de la méthode abstraite update
    public function update(): self|false
    {
        // Mettre à jour d'abord le produit de base
        if (!$this->updateBaseProduct()) {
            return false;
        }

        try {
            $pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8mb4', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("
                UPDATE electronic 
                SET brand = :brand, 
                    warranty_fee = :warranty_fee
                WHERE product_id = :product_id
            ");

            $stmt->execute([
                'product_id' => $this->getId(),
                'brand' => $this->brand,
                'warranty_fee' => $this->warranty_fee
            ]);

            return $this;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Implémentation de StockableInterface
    public function addStocks(int $stock): self
    {
        $currentQuantity = $this->getQuantity();
        $this->setQuantity($currentQuantity + $stock);
        return $this;
    }

    public function removeStocks(int $stock): self
    {
        $currentQuantity = $this->getQuantity();
        $newQuantity = $currentQuantity - $stock;

        // Empêcher les quantités négatives
        if ($newQuantity < 0) {
            $newQuantity = 0;
        }

        $this->setQuantity($newQuantity);
        return $this;
    }
}
