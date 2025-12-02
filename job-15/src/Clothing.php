<?php

namespace App;

use App\Abstract\AbstractProduct;
use App\Interface\StockableInterface;
use PDO;
use PDOException;

// Classe Clothing dérivant de AbstractProduct et implémentant StockableInterface
class Clothing extends AbstractProduct implements StockableInterface
{
    private string $size;
    private string $color;
    private string $type;
    private int $material_fee;

    // Constructeur
    public function __construct(
        int $id,
        string $name,
        array $photos,
        int $price,
        string $description,
        int $quantity,
        int $category_id,
        string $size,
        string $color,
        string $type,
        int $material_fee
    ) {
        // Appel du constructeur parent
        parent::__construct($id, $name, $photos, $price, $description, $quantity, $category_id);

        $this->size = $size;
        $this->color = $color;
        $this->type = $type;
        $this->material_fee = $material_fee;
    }

    // Routeurs - Getters
    public function getSize(): string
    {
        return $this->size;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getMaterialFee(): int
    {
        return $this->material_fee;
    }

    // Routeurs - Setters
    public function setSize(string $size): self
    {
        $this->size = $size;
        return $this;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;
        return $this;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function setMaterialFee(int $material_fee): self
    {
        $this->material_fee = $material_fee;
        return $this;
    }

    // Implémentation de la méthode abstraite findOneById
    public function findOneById(int $id): self|false
    {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8mb4', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Jointure entre product et clothing
            $stmt = $pdo->prepare("
                SELECT p.*, c.size, c.color, c.type, c.material_fee
                FROM product p
                INNER JOIN clothing c ON p.id = c.product_id
                WHERE p.id = :id
            ");

            $stmt->execute(['id' => $id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                return false;
            }

            // Créer une instance de Clothing avec toutes les données
            return new Clothing(
                $data['id'],
                $data['name'],
                json_decode($data['photos'], true),
                $data['price'],
                $data['description'],
                $data['quantity'],
                $data['category_id'],
                $data['size'],
                $data['color'],
                $data['type'],
                $data['material_fee']
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

            // Jointure entre product et clothing
            $stmt = $pdo->query("
                SELECT p.*, c.size, c.color, c.type, c.material_fee
                FROM product p
                INNER JOIN clothing c ON p.id = c.product_id
            ");

            $products = [];
            while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $products[] = new Clothing(
                    $data['id'],
                    $data['name'],
                    json_decode($data['photos'], true),
                    $data['price'],
                    $data['description'],
                    $data['quantity'],
                    $data['category_id'],
                    $data['size'],
                    $data['color'],
                    $data['type'],
                    $data['material_fee']
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
                INSERT INTO clothing (product_id, size, color, type, material_fee)
                VALUES (:product_id, :size, :color, :type, :material_fee)
            ");

            $stmt->execute([
                'product_id' => $this->getId(),
                'size' => $this->size,
                'color' => $this->color,
                'type' => $this->type,
                'material_fee' => $this->material_fee
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
                UPDATE clothing 
                SET size = :size, 
                    color = :color, 
                    type = :type, 
                    material_fee = :material_fee
                WHERE product_id = :product_id
            ");

            $stmt->execute([
                'product_id' => $this->getId(),
                'size' => $this->size,
                'color' => $this->color,
                'type' => $this->type,
                'material_fee' => $this->material_fee
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
