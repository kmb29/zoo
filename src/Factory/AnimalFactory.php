<?php
namespace App\Factory;

use App\Entity\Animal\Crocodile;
use App\Entity\Animal\Elephant;
use App\Entity\Animal\Lion;
use App\Entity\Animal\Interfaces\AnimalInterface;

class AnimalFactory
{
    public static function create(string $species): AnimalInterface
    {
        return match (strtolower($species)) {
            'lion' => new Lion($species),
            'elephant' => new Elephant($species),
            'crocodile' => new Crocodile($species),
            default => throw new \InvalidArgumentException("Неизвестный вид: $species"),
        };
    }
}
