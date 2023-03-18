<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    // #[Assert\NotBlank(message: 'Le produit ne peut pas être vide')]
    // // #[Assert\Length(min = 3, max = 255, minMessage = "Le nom du produit doit avoir au moins trois caractères", maxMessage = "le nom est trop grand!")]
    // #[Assert\Length(
    //     min: 3,
    //     max: 255,
    // )]
    private ?string $name = null;

    #[ORM\Column]
    // #[Assert\NotBlank(message: 'This value should not be blank')]
    private ?int $price = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Category $category = null;

    #[ORM\Column(length: 255)]
    private ?string $mainPicture = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $shortDescription = null;

    // public static function loadValidatorMetadata(ClassMetadata $Metadata)
    // {
    //     $Metadata->addPropertyConstraints('name', [
    //         new NotBlank(['message' => "le nom du produit est obligatoire"]),
    //         new Length(['min' => 3, 'minMessage' => "Le produit doit contenir au moins trois lettres"])
    //     ]);
    // }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUppercaseName(): string
    {
        return strtoupper($this->name);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getMainPicture(): ?string
    {
        return $this->mainPicture;
    }

    public function setMainPicture(string $mainPicture): self
    {
        $this->mainPicture = $mainPicture;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }
}
