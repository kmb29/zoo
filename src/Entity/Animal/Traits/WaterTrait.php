<?php
namespace App\Entity\Animal\Traits;

trait WaterTrait {
    public function water(): string {
        return "Поливает себя!";
    }
}
