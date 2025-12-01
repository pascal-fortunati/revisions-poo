<?php

namespace App;

use App\Interface\EntityInterface;
use PDO;
use PDOException;

/**
 * Classe permettant de gérer une collection d'entités
 */
class EntityCollection
{
    /**
     * @var EntityInterface[] Tableau d'entités
     */
    private array $entities = [];

    /**
     * Ajoute une nouvelle entité à la collection
     * 
     * @param EntityInterface $entity L'entité à ajouter
     * @return self
     */
    public function add(EntityInterface $entity): self
    {
        // Vérifie si l'entité n'est pas déjà dans la collection (par ID)
        $entityId = $entity->getId();

        // Si l'entité a un ID valide, on vérifie qu'elle n'existe pas déjà
        if ($entityId > 0) {
            foreach ($this->entities as $existingEntity) {
                if ($existingEntity->getId() === $entityId && get_class($existingEntity) === get_class($entity)) {
                    // L'entité existe déjà, on ne l'ajoute pas
                    return $this;
                }
            }
        }

        $this->entities[] = $entity;
        return $this;
    }

    /**
     * Retire une entité présente dans la collection
     * 
     * @param EntityInterface $entity L'entité à retirer
     * @return self
     */
    public function remove(EntityInterface $entity): self
    {
        $entityId = $entity->getId();
        $entityClass = get_class($entity);

        foreach ($this->entities as $key => $existingEntity) {
            // On compare par ID et par classe pour être sûr de retirer la bonne entité
            if ($existingEntity->getId() === $entityId && get_class($existingEntity) === $entityClass) {
                unset($this->entities[$key]);
                // Réindexe le tableau pour éviter les trous dans les clés
                $this->entities = array_values($this->entities);
                break;
            }
        }

        return $this;
    }

    /**
     * Récupère toutes les entités de la collection
     * 
     * @return EntityInterface[] Tableau des entités
     */
    public function getAll(): array
    {
        return $this->entities;
    }

    /**
     * Compte le nombre d'entités dans la collection
     * 
     * @return int Le nombre d'entités
     */
    public function count(): int
    {
        return count($this->entities);
    }

    /**
     * Vérifie si la collection est vide
     * 
     * @return bool True si vide, false sinon
     */
    public function isEmpty(): bool
    {
        return empty($this->entities);
    }

    /**
     * Vide complètement la collection
     * 
     * @return self
     */
    public function clear(): self
    {
        $this->entities = [];
        return $this;
    }

    /**
     * Récupère une entité par son index
     * 
     * @param int $index L'index de l'entité
     * @return EntityInterface|null L'entité ou null si l'index n'existe pas
     */
    public function get(int $index): ?EntityInterface
    {
        return $this->entities[$index] ?? null;
    }

    /**
     * Trouve une entité par son ID
     * 
     * @param int $id L'ID de l'entité recherchée
     * @return EntityInterface|null L'entité trouvée ou null
     */
    public function findById(int $id): ?EntityInterface
    {
        foreach ($this->entities as $entity) {
            if ($entity->getId() === $id) {
                return $entity;
            }
        }
        return null;
    }

    /**
     * Récupère toutes les entités liées à l'entité de base
     * Par exemple : récupérer tous les produits (Clothing et Electronic) liés à une catégorie
     * 
     * @param EntityInterface $baseEntity L'entité de base (par exemple une Category)
     * @param string $entityType Le type d'entité à récupérer ('product' pour Clothing et Electronic)
     * @return self
     */
    public function retrieve(EntityInterface $baseEntity, string $entityType = 'product'): self
    {
        // Vide la collection actuelle
        $this->clear();

        // Si l'entité de base est une Category et qu'on veut récupérer des produits
        if ($baseEntity instanceof Category && $entityType === 'product') {
            $this->retrieveProductsByCategory($baseEntity);
        }

        return $this;
    }

    /**
     * Récupère tous les produits (Clothing et Electronic) liés à une catégorie
     * 
     * @param Category $category La catégorie
     * @return void
     */
    private function retrieveProductsByCategory(Category $category): void
    {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=draft-shop;charset=utf8mb4', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Récupération des vêtements (Clothing) liés à cette catégorie
            $stmt = $pdo->prepare("
                SELECT p.*, c.size, c.color, c.type, c.material_fee 
                FROM product p
                INNER JOIN clothing c ON p.id = c.product_id
                WHERE p.category_id = :category_id
            ");
            $stmt->execute(['category_id' => $category->getId()]);
            $clothingData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($clothingData as $data) {
                $clothing = new Clothing(
                    $data['id'],
                    $data['name'],
                    json_decode($data['photos'], true) ?? [],
                    $data['price'],
                    $data['description'],
                    $data['quantity'],
                    $data['category_id'],
                    $data['size'],
                    $data['color'],
                    $data['type'],
                    $data['material_fee']
                );
                $this->add($clothing);
            }

            // Récupération des produits électroniques (Electronic) liés à cette catégorie
            $stmt = $pdo->prepare("
                SELECT p.*, e.brand, e.warranty_fee 
                FROM product p
                INNER JOIN electronic e ON p.id = e.product_id
                WHERE p.category_id = :category_id
            ");
            $stmt->execute(['category_id' => $category->getId()]);
            $electronicData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($electronicData as $data) {
                $electronic = new Electronic(
                    $data['id'],
                    $data['name'],
                    json_decode($data['photos'], true) ?? [],
                    $data['price'],
                    $data['description'],
                    $data['quantity'],
                    $data['category_id'],
                    $data['brand'],
                    $data['warranty_fee']
                );
                $this->add($electronic);
            }
        } catch (PDOException $e) {
            // En cas d'erreur, la collection reste vide
        }
    }
}
