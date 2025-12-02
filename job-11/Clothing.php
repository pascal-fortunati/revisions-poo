<?php
require_once 'Product.php';

// Classe Clothing héritant de Product
class Clothing extends Product
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

    // Getters
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

    // Setters
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

    // Surcharge de la méthode create pour inclure les données spécifiques aux vêtements
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

    // Surcharge de la méthode update pour inclure les données spécifiques aux vêtements
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
}
