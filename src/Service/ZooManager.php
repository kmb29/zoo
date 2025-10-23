<?php
namespace App\Service;

use App\Entity\Animal;
use App\Entity\Cell;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CellRepository;
use App\Factory\AnimalFactory;

class ZooManager
{
    public function __construct(private EntityManagerInterface $em, private CellRepository $cellRepository) {}

    public function createCell(string $type): Cell
    {
        $cell = new Cell($type);
        $this->em->persist($cell);
        $this->em->flush();
        return $cell;
    }



    public function cleanCell(int $id): string
    {
        $cell = $this->em->getRepository(Cell::class)->find($id);
        if (!$cell) throw new \RuntimeException('Клетка не найдена');

        if (!$cell->canBeCleaned()) {
            throw new \LogicException('Нельзя почистить клетку, пока в ней животные');
        }

        return "Клетка почищена!";

    }

    public function addAnimal(int $cellId, string $species): Animal
    {
        $cell = $this->em->getRepository(Cell::class)->find($cellId);
        if (!$cell) throw new \RuntimeException('Клетка не найдена');

        $animal = AnimalFactory::create($species);

        $cell->addAnimal($animal);

        $this->em->persist($animal);
        $this->em->flush();

        return $animal;
    }

    public function removeAnimal(int $animalId): void
    {
        $animal = $this->em->getRepository(Animal::class)->find($animalId);
        if (!$animal) {
            throw new \RuntimeException('Животное не найдено');
        }

        // Разрываем связь с клеткой, если она есть
        $cell = $animal->getCell();
        if ($cell) {
            $cell->removeAnimal($animal);
        }

        $this->em->remove($animal);
        $this->em->flush();
    }


    public function feedAnimal(int $animalId): string
    {
        $animal = $this->em->getRepository(Animal::class)->find($animalId);
        if (!$animal) {
            throw new \RuntimeException('Животное, которое вы хотите покормить, не найдено');
        }
        return $animal->eat();
    }

    public function performAction(int $animalId, string $action): string
    {
        $animal = $this->em->getRepository(Animal::class)->find($animalId);
        if (!$animal) {
            throw new \RuntimeException('Животное, которому вы отдали команду, не найдено');
        }
        if (method_exists($animal, $action)) {
            return $animal->$action();
        } else {
            throw new \LogicException('Это животное не может выполнить данное действие!');
        }
    }


    public function getZoo(): array
    {
        $cells = $this->cellRepository->findAll();
        $data = [];

        foreach ($cells as $cell) {
            $animals = [];
            foreach ($cell->getAnimals() as $animal) {
                $animals[] = [
                    'id' => $animal->getId(),
                    'species' => $animal->getSpecies(),
                ];
            }

            $data[] = [
                'id' => $cell->getId(),
                'type' => $cell->getType(),
                'animals' => $animals,
            ];
        }

        return $data;
    }

}
