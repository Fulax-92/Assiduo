<?php

namespace App\Entity;

use App\Repository\MotifRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MotifRepository::class)]
#[ORM\Table(name: 'motif')]
#[UniqueEntity(fields: ['code'], message: 'Cette valeur est déjà utilisée.')]
class Motif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 50)]
    private ?string $code = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    private ?string $libelle = null;

    #[ORM\Column(options: ['default' => false])]
    private bool $justificatifRequis = false;

    #[ORM\Column(options: ['default' => true])]
    private bool $actif = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function isJustificatifRequis(): bool
    {
        return $this->justificatifRequis;
    }

    public function setJustificatifRequis(bool $justificatifRequis): static
    {
        $this->justificatifRequis = $justificatifRequis;

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
