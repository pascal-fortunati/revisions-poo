<?php
require_once 'Category.php';

// Classe Product
class Product
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

    // Méthode pour trouver un produit par son ID et hydrater l'instance courante
    public function findOneById(int $id): Product|false
    {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8mb4', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT * FROM product WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $productData = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$productData) {
                return false;
            }

            // Hydratation de l'instance courante avec les données récupérées
            $this->id = $productData['id'];
            $this->name = $productData['name'];
            $this->photos = json_decode($productData['photos'], true) ?? [];
            $this->price = $productData['price'];
            $this->description = $productData['description'];
            $this->quantity = $productData['quantity'];
            $this->category_id = $productData['category_id'];

            // Hydratation des timestamps
            if (isset($productData['createdAt'])) {
                $this->createdAt = new DateTime($productData['createdAt']);
            }
            if (isset($productData['updatedAt'])) {
                $this->updatedAt = new DateTime($productData['updatedAt']);
            }

            return $this;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Méthode pour récupérer tous les produits
    public function findAll(): array
    {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8mb4', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->query("SELECT * FROM product");
            $productsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $products = [];

            foreach ($productsData as $productData) {
                $product = new Product(
                    $productData['id'],
                    $productData['name'],
                    json_decode($productData['photos'], true) ?? [],
                    $productData['price'],
                    $productData['description'],
                    $productData['quantity'],
                    $productData['category_id']
                );

                // Hydratation des timestamps
                if (isset($productData['createdAt'])) {
                    $product->setCreatedAt(new DateTime($productData['createdAt']));
                }
                if (isset($productData['updatedAt'])) {
                    $product->setUpdatedAt(new DateTime($productData['updatedAt']));
                }

                $products[] = $product;
            }

            return $products;
        } catch (PDOException $e) {
            return [];
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
