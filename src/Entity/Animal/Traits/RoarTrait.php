<?php
namespace App\Entity\Animal\Traits;

trait RoarTrait {
    public function roar(): string {
        return "Рычит!";
    }
}
