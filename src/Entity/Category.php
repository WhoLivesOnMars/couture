<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $icon = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $image1Url = null;

    #[ORM\Column(length: 255)]
    private ?string $image1Alt = null;

    #[ORM\Column(length: 255)]
    private ?string $image2Url = null;

    #[ORM\Column(length: 255)]
    private ?string $image2Alt = null;

    /**
     * @var Collection<int, Subcategory>
     */
    #[ORM\OneToMany(targetEntity: Subcategory::class, mappedBy: 'category')]
    private Collection $subcategories;

    public function __construct()
    {
        $this->subcategories = new ArrayCollection();
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

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImage1Url(): ?string
    {
        return $this->image1Url;
    }

    public function setImage1Url(string $image1Url): static
    {
        $this->image1Url = $image1Url;

        return $this;
    }

    public function getImage1Alt(): ?string
    {
        return $this->image1Alt;
    }

    public function setImage1Alt(string $image1Alt): static
    {
        $this->image1Alt = $image1Alt;

        return $this;
    }

    public function getImage2Url(): ?string
    {
        return $this->image2Url;
    }

    public function setImage2Url(string $image2Url): static
    {
        $this->image2Url = $image2Url;

        return $this;
    }

    public function getImage2Alt(): ?string
    {
        return $this->image2Alt;
    }

    public function setImage2Alt(string $image2Alt): static
    {
        $this->image2Alt = $image2Alt;

        return $this;
    }

    /**
     * @return Collection<int, Subcategory>
     */
    public function getSubcategories(): Collection
    {
        return $this->subcategories;
    }

    public function addSubcategory(Subcategory $subcategory): static
    {
        if (!$this->subcategories->contains($subcategory)) {
            $this->subcategories->add($subcategory);
            $subcategory->setCategory($this);
        }

        return $this;
    }

    public function removeSubcategory(Subcategory $subcategory): static
    {
        if ($this->subcategories->removeElement($subcategory)) {
            // set the owning side to null (unless already changed)
            if ($subcategory->getCategory() === $this) {
                $subcategory->setCategory(null);
            }
        }

        return $this;
    }
}
