<?php
namespace App\Entity;

use App\Entity\Animal\Interfaces\PredatorInterface;
use App\Entity\Animal\Interfaces\HerbivoreInterface;
use App\Repository\CellRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CellRepository::class)]
class Cell
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private string $type; // predator | herbivore

    #[ORM\OneToMany(mappedBy: 'cell', targetEntity: Animal::class, cascade: ['persist', 'remove'])]
    private Collection $animals;

    public function __construct(string $type)
    {
        $this->type = $type;
        $this->animals = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getType(): string { return $this->type; }

    /** @return Animal[] */
    public function getAnimals(): array { return $this->animals->toArray(); }

    public function addAnimal(Animal $animal): void
    {
        if ($this->type === 'predator' && $animal instanceof HerbivoreInterface) {
            throw new \LogicException('Нельзя добавить травоядное к хищникам.');
        }
        if ($this->type === 'herbivore' && $animal instanceof PredatorInterface) {
            throw new \LogicException('Нельзя добавить хищника к травоядным.');
        }

        $this->animals->add($animal);
        $animal->setCell($this);
    }

    public function removeAnimal(Animal $animal): void
    {
        $this->animals->removeElement($animal);
    }

    public function canBeCleaned(): bool
    {
        return $this->animals->isEmpty();
    }
}
