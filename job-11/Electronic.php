<?php
require_once 'Product.php';

// Classe Electronic héritant de Product
class Electronic extends Product
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

    // Getters
    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getWarrantyFee(): int
    {
        return $this->warranty_fee;
    }

    // Setters
    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
    }

    public function setWarrantyFee(int $warranty_fee): void
    {
        $this->warranty_fee = $warranty_fee;
    }

    // Surcharge de la méthode create pour inclure les données spécifiques aux électroniques
    public function create(): Product|false
    {
        // Créer d'abord le produit parent
        $result = parent::create();

        if ($result === false) {
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

    // Surcharge de la méthode update pour inclure les données spécifiques aux électroniques
    public function update(): Product|false
    {
        // Mettre à jour d'abord le produit parent
        $result = parent::update();

        if ($result === false) {
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
}
