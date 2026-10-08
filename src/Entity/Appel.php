<?php

namespace App\Entity;

use App\Repository\AppelRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AppelRepository::class)]
#[ORM\Table(name: 'appel')]
#[ORM\UniqueConstraint(name: 'uniq_appel_date_creneau', columns: ['date_appel', 'creneau_id'])]
#[UniqueEntity(fields: ['dateAppel', 'creneau'], errorPath: 'dateAppel', message: 'Un appel existe déjà pour ce créneau à cette date.')]
class Appel
{
    public const STATUT_BROUILLON = 'BROUILLON';
    public const STATUT_EN_COURS = 'EN_COURS';
    public const STATUT_VALIDE = 'VALIDE';
    public const STATUT_VERROUILLE = 'VERROUILLE';
    public const STATUT_ANNULE = 'ANNULE';
    public const STATUTS = [self::STATUT_BROUILLON, self::STATUT_EN_COURS, self::STATUT_VALIDE, self::STATUT_VERROUILLE, self::STATUT_ANNULE];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Assert\NotNull]
    private ?\DateTimeImmutable $dateAppel = null;

    #[ORM\Column(length: 30, options: ['default' => self::STATUT_BROUILLON])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 30)]
    #[Assert\Choice(choices: self::STATUTS)]
    private string $statut = self::STATUT_BROUILLON;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $ouvertLe = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $valideLe = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $verrouilleLe = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Utilisateur $creePar = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    private ?Utilisateur $validePar = null;

    #[ORM\ManyToOne(targetEntity: Creneau::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Creneau $creneau = null;

    public function __construct()
    {
        $this->ouvertLe = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateAppel(): ?\DateTimeImmutable
    {
        return $this->dateAppel;
    }

    public function setDateAppel(\DateTimeImmutable $dateAppel): static
    {
        $this->dateAppel = $dateAppel;

        return $this;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getOuvertLe(): ?\DateTimeImmutable
    {
        return $this->ouvertLe;
    }

    public function setOuvertLe(\DateTimeImmutable $ouvertLe): static
    {
        $this->ouvertLe = $ouvertLe;

        return $this;
    }

    public function getValideLe(): ?\DateTimeImmutable
    {
        return $this->valideLe;
    }

    public function setValideLe(?\DateTimeImmutable $valideLe): static
    {
        $this->valideLe = $valideLe;

        return $this;
    }

    public function getVerrouilleLe(): ?\DateTimeImmutable
    {
        return $this->verrouilleLe;
    }

    public function setVerrouilleLe(?\DateTimeImmutable $verrouilleLe): static
    {
        $this->verrouilleLe = $verrouilleLe;

        return $this;
    }

    public function getCreePar(): ?Utilisateur
    {
        return $this->creePar;
    }

    public function setCreePar(Utilisateur $creePar): static
    {
        $this->creePar = $creePar;

        return $this;
    }

    public function getValidePar(): ?Utilisateur
    {
        return $this->validePar;
    }

    public function setValidePar(?Utilisateur $validePar): static
    {
        $this->validePar = $validePar;

        return $this;
    }

    public function getCreneau(): ?Creneau
    {
        return $this->creneau;
    }

    public function setCreneau(Creneau $creneau): static
    {
        $this->creneau = $creneau;

        return $this;
    }
}
