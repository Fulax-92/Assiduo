<?php

namespace App\Entity;

use App\Repository\EleveRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EleveRepository::class)]
#[ORM\Table(name: 'eleve')]
#[UniqueEntity(fields: ['ine'], message: 'Cette valeur est déjà utilisée.')]
class Eleve
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 11, unique: true, nullable: true)]
    #[Assert\Length(max: 11)]
    private ?string $ine = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Assert\NotNull]
    #[Assert\LessThan(value: 'today', message: 'La date de naissance doit être dans le passé.')]
    private ?\DateTimeImmutable $dateNaissance = null;

    #[ORM\Column(options: ['default' => true])]
    private bool $actif = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIne(): ?string
    {
        return $this->ine;
    }

    public function setIne(?string $ine): static
    {
        $this->ine = $ine;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeImmutable
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeImmutable $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function isActif(): bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): static
    {
        $this->actif = $actif;

        return $this;
    }
}
