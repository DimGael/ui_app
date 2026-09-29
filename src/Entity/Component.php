<?php

namespace App\Entity;

use App\Repository\ComponentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComponentRepository::class)]
class Component
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null {
        get {
            return $this->id;
        }
    }

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $htmlcode = null;

    #[ORM\ManyToOne(inversedBy: 'components')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Style $style = null;

    #[ORM\ManyToOne(inversedBy: 'Components')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getHtmlcode(): ?string
    {
        return $this->htmlcode;
    }

    public function setHtmlcode(string $htmlcode): static
    {
        $this->htmlcode = $htmlcode;

        return $this;
    }

    public function getStyle(): ?Style
    {
        return $this->style;
    }

    public function setStyle(?Style $style): static
    {
        $this->style = $style;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }
}
