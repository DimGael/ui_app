<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Component>
     */
    #[ORM\OneToMany(targetEntity: Component::class, mappedBy: 'category')]
    private Collection $Components {
        get {
            return $this->Components;
        }
    }

    public function __construct()
    {
        $this->Components = new ArrayCollection();
    }

    /**
     * @return int|null
     */
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

    public function addComponent(Component $component): static
    {
        if (!$this->Components->contains($component)) {
            $this->Components->add($component);
            $component->setCategory($this);
        }

        return $this;
    }

    public function removeComponent(Component $component): static
    {
        if ($this->Components->removeElement($component)) {
            // set the owning side to null (unless already changed)
            if ($component->getCategory() === $this) {
                $component->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString():string
    {
        return $this->getName() ?? '';
    }
}
