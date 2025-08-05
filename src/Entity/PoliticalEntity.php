<?php

namespace App\Entity;

use App\Repository\PoliticalEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PoliticalEntityRepository::class)]
class PoliticalEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    private ?string $color = null;

    /**
     * @var Collection<int, TemporalBoundary>
     */
    #[ORM\OneToMany(targetEntity: TemporalBoundary::class, mappedBy: 'PoliticalEntity', orphanRemoval: true)]
    private Collection $temporalBoundaries;

    public function __construct()
    {
        $this->temporalBoundaries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

                public function __toString(): string
    {
        return $this->name ?? ''; // Return the 'name' property. Use null coalescing for safety.
    }

                /**
                 * @return Collection<int, TemporalBoundary>
                 */
                public function getTemporalBoundaries(): Collection
                {
                    return $this->temporalBoundaries;
                }

                public function addTemporalBoundary(TemporalBoundary $temporalBoundary): static
                {
                    if (!$this->temporalBoundaries->contains($temporalBoundary)) {
                        $this->temporalBoundaries->add($temporalBoundary);
                        $temporalBoundary->setPoliticalEntity($this);
                    }

                    return $this;
                }

                public function removeTemporalBoundary(TemporalBoundary $temporalBoundary): static
                {
                    if ($this->temporalBoundaries->removeElement($temporalBoundary)) {
                        // set the owning side to null (unless already changed)
                        if ($temporalBoundary->getPoliticalEntity() === $this) {
                            $temporalBoundary->setPoliticalEntity(null);
                        }
                    }

                    return $this;
                }
}
