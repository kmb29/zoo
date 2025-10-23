<?php
namespace App\Entity\Animal;

use App\Entity\Animal;
use App\Entity\Animal\Interfaces\HerbivoreInterface;
use App\Entity\Animal\Traits\WaterTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Elephant extends Animal implements HerbivoreInterface {
    use WaterTrait;
    public function eat(): string {
        return "Слон ест траву.";
    }
}
