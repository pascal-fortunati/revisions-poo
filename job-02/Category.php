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

    // Routeurs - Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
        $this->updateTimestamp();
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
        $this->updateTimestamp();
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    // Méthode privée pour mettre à jour le timestamp
    private function updateTimestamp(): void
    {
        $this->updatedAt = new DateTime();
    }
}
// Création d'une catégorie et d'un produit
$category = new Category(
    1,
    "Informatique",
    "Tous les produits informatiques : ordinateurs, accessoires, etc."
);

// Création d'un produit
$product = new Product(
    1,
    "Ordinateur portable",
    ["photo1.jpg", "photo2.jpg", "photo3.jpg"],
    99900, // Prix en centimes (999,00 €)
    "Un excellent ordinateur portable pour le travail et les loisirs",
    15,
    $category->getId() // Utilisation de l'ID de la catégorie
);
