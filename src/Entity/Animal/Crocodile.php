<?php
namespace App\Entity\Animal;

use App\Entity\Animal;
use App\Entity\Animal\Interfaces\PredatorInterface;
use App\Entity\Animal\Traits\RoarTrait;
use App\Entity\Animal\Traits\SwimTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Crocodile extends Animal implements PredatorInterface {
    use RoarTrait, SwimTrait;
    public function eat(): string {
        return "Крокодил ест мясо.";
    }
}
