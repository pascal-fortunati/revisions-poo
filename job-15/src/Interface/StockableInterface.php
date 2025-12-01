<?php

namespace App\Interface;

// Interface pour la gestion des stocks
interface StockableInterface
{
    // Ajouter du stock
    public function addStocks(int $stock): self;

    // Retirer du stock
    public function removeStocks(int $stock): self;
}
