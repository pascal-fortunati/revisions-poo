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
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
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

    /**
     * Récupère tous les produits (Clothing et Electronic) liés à cette catégorie
     * 
     * @return EntityCollection Collection de tous les produits de cette catégorie
     */
    public function getProducts(): EntityCollection
    {
        $collection = new EntityCollection();
        return $collection->retrieve($this, 'product');
    }
}
