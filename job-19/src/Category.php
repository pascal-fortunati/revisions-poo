<?php

namespace App;

use App\Interface\EntityInterface;
use DateTime;

// Classe Category implémente EntityInterface
class Category implements EntityInterface
{
    private int $id;
    private string $name;
    private string $description;
    private DateTime $createdAt;
    private DateTime $updatedAt;
    private EntityCollection $products;

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
        $this->products = new EntityCollection();
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

    /**
     * Récupère la collection de produits liés à cette catégorie
     * 
     * @return EntityCollection Collection de tous les produits de cette catégorie
     */
    public function getProducts(): EntityCollection
    {
        return $this->products;
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

    /**
     * Définit la collection de produits
     * 
     * @param EntityCollection $products La collection de produits
     * @return void
     */
    public function setProducts(EntityCollection $products): self
    {
        $this->products = $products;
        return $this;
    }

    /**
     * Charge tous les produits (Clothing et Electronic) liés à cette catégorie depuis la base de données
     * et les ajoute à la collection
     * 
     * @return self
     */
    public function loadProducts(): self
    {
        // Vide la collection actuelle
        $this->products->clear();

        // Utilise la méthode retrieve pour charger les produits depuis la base
        $this->products->retrieve($this, 'product');

        return $this;
    }

    /**
     * Ajoute un produit à la collection de produits de cette catégorie
     * 
     * @param EntityInterface $product Le produit à ajouter
     * @return self
     */
    public function addProduct(EntityInterface $product): self
    {
        $this->products->add($product);
        return $this;
    }

    /**
     * Retire un produit de la collection de produits de cette catégorie
     * 
     * @param EntityInterface $product Le produit à retirer
     * @return self
     */
    public function removeProduct(EntityInterface $product): self
    {
        $this->products->remove($product);
        return $this;
    }

    // Méthode privée pour mettre à jour le timestamp
    private function updateTimestamp(): void
    {
        $this->updatedAt = new DateTime();
    }
}
