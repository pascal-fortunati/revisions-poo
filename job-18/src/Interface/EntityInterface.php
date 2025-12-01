<?php

namespace App\Interface;

interface EntityInterface
{
    /**
     * Récupère l'identifiant de l'entité
     * 
     * @return int L'identifiant de l'entité
     */
    public function getId(): int;

    /**
     * Définit l'identifiant de l'entité
     * 
     * @param int $id L'identifiant à définir
     * @return self
     */
    public function setId(int $id): self;
}
