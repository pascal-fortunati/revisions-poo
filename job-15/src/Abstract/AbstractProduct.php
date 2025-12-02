<?php

namespace App\Abstract;

use App\Category;
use DateTime;
use PDO;
use PDOException;

// Classe abstraite AbstractProduct
abstract class AbstractProduct
{
    private int $id;
    private string $name;
    private array $photos;
    private int $price;
    private string $description;
    private int $quantity;
    private int $category_id;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    // Constructeur
    public function __construct(
        int $id,
        string $name,
        array $photos,
        int $price,
        string $description,
        int $quantity,
        int $category_id
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->photos = $photos;
        $this->price = $price;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->category_id = $category_id;
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    // Routeurs - Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPhotos(): array
    {
        return $this->photos;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    // Méthode pour récupérer la catégorie associée au produit
    public function getCategory(): ?Category
    {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8mb4', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT * FROM category WHERE id = :id");
            $stmt->execute(['id' => $this->category_id]);
            $categoryData = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$categoryData) {
                return null;
            }

            $category = new Category(
                $categoryData['id'],
                $categoryData['name'],
                $categoryData['description']
            );

            // Hydratation des timestamps
            if (isset($categoryData['createdAt'])) {
                $category->setCreatedAt(new DateTime($categoryData['createdAt']));
            }
            if (isset($categoryData['updatedAt'])) {
                $category->setUpdatedAt(new DateTime($categoryData['updatedAt']));
            }

            return $category;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    // Méthodes abstraites - doivent être implémentées dans les classes enfants
    abstract public function findOneById(int $id): self|false;
    abstract public function findAll(): array;
    abstract public function create(): self|false;
    abstract public function update(): self|false;

    // Méthode protégée pour créer le produit de base (utilisée par les classes enfants)
    protected function createBaseProduct(): bool
    {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8mb4', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Mise à jour des timestamps avant insertion
            $this->createdAt = new DateTime();
            $this->updatedAt = new DateTime();

            $stmt = $pdo->prepare("
                INSERT INTO product (name, photos, price, description, quantity, category_id, createdAt, updatedAt)
                VALUES (:name, :photos, :price, :description, :quantity, :category_id, :createdAt, :updatedAt)
            ");

            $stmt->execute([
                'name' => $this->name,
                'photos' => json_encode($this->photos),
                'price' => $this->price,
                'description' => $this->description,
                'quantity' => $this->quantity,
                'category_id' => $this->category_id,
                'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
                'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s')
            ]);

            // Récupération de l'ID auto-généré et mise à jour de l'instance
            $this->id = (int) $pdo->lastInsertId();

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Méthode protégée pour mettre à jour le produit de base (utilisée par les classes enfants)
    protected function updateBaseProduct(): bool
    {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8mb4', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Mise à jour du timestamp avant update
            $this->updatedAt = new DateTime();

            $stmt = $pdo->prepare("
                UPDATE product 
                SET name = :name, 
                    photos = :photos, 
                    price = :price, 
                    description = :description, 
                    quantity = :quantity, 
                    category_id = :category_id, 
                    updatedAt = :updatedAt
                WHERE id = :id
            ");

            return $stmt->execute([
                'id' => $this->id,
                'name' => $this->name,
                'photos' => json_encode($this->photos),
                'price' => $this->price,
                'description' => $this->description,
                'quantity' => $this->quantity,
                'category_id' => $this->category_id,
                'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s')
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Routeurs - Setters
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        $this->updateTimestamp();
        return $this;
    }

    public function setPhotos(array $photos): self
    {
        $this->photos = $photos;
        $this->updateTimestamp();
        return $this;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;
        $this->updateTimestamp();
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        $this->updateTimestamp();
        return $this;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        $this->updateTimestamp();
        return $this;
    }

    public function setCategoryId(int $category_id): self
    {
        $this->category_id = $category_id;
        $this->updateTimestamp();
        return $this;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function setUpdatedAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    // Méthode pour mettre à jour le timestamp de mise à jour
    private function updateTimestamp(): void
    {
        $this->updatedAt = new DateTime();
    }
}
