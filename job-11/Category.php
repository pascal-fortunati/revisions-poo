<?php
require_once 'Product.php';

// Classe Category
class Category
{
    private int $id;
    private string $name;
    private string $description;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    // Constructeur
    public function __construct(
        int $id,
        string $name,
        string $description
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    // Méthode pour récupérer tous les produits de cette catégorie
    public function getProducts(): array
    {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8mb4', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT * FROM product WHERE category_id = :category_id");
            $stmt->execute(['category_id' => $this->id]);
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

    public function setDescription(string $description): self
    {
        $this->description = $description;
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

    // Méthode privée pour mettre à jour le timestamp
    private function updateTimestamp(): void
    {
        $this->updatedAt = new DateTime();
    }
}
