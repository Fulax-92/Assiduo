<?php

namespace App\Entity;

use App\Repository\MotifRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MotifRepository::class)]
#[UniqueEntity(fields: ['code'])]
class Motif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 50)]
    #[Assert\Regex(pattern: '/^[A-Z][A-Z_]*$/', message: 'Le code doit être en MAJUSCULES_AVEC_UNDERSCORES.')]
    #[ORM\Column(type: Types::STRING, length: 50, unique: true)]
    private ?string $code = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    #[ORM\Column(type: Types::STRING, length: 100)]
    private ?string $libelle = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $justificatifRequis = false;

    #[ORM\Column(type: Types::BOOLEAN)]
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
