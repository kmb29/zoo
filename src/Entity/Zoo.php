<?php
namespace App\Entity;

class Zoo {
    /** @var Cell[] */
    private array $cells = [];

    public function addCell(Cell $cell): void {
        $this->cells[] = $cell;
    }

    public function getCells(): array {
        return $this->cells;
    }
}
