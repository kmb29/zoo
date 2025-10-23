<?php
namespace App\Entity\Animal;

use App\Entity\Animal;
use App\Entity\Animal\Traits\RoarTrait;
use App\Entity\Animal\Interfaces\PredatorInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Lion extends Animal implements PredatorInterface {
    use RoarTrait;
    public function eat(): string {
        return "Лев ест мясо.";
    }
}
