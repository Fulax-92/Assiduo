<?php

namespace App\Entity;

use App\Repository\JustificatifRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: JustificatifRepository::class)]
#[ORM\Table(name: 'justificatif')]
class Justificatif
{
    public const SOURCE_PARENT = 'PARENT';
    public const SOURCE_ELEVE = 'ELEVE';
    public const SOURCE_ADMINISTRATION = 'ADMINISTRATION';
    public const SOURCES = [self::SOURCE_PARENT, self::SOURCE_ELEVE, self::SOURCE_ADMINISTRATION];

    public const STATUT_DEPOSE = 'DEPOSE';
    public const STATUT_EXPLOITE = 'EXPLOITE';
    public const STATUT_REJETE = 'REJETE';
    public const STATUT_ARCHIVE = 'ARCHIVE';
    public const STATUTS = [self::STATUT_DEPOSE, self::STATUT_EXPLOITE, self::STATUT_REJETE, self::STATUT_ARCHIVE];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 30)]
    #[Assert\Choice(choices: self::SOURCES)]
    private ?string $source = null;

    #[ORM\Column(length: 30, options: ['default' => self::STATUT_DEPOSE])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 30)]
    #[Assert\Choice(choices: self::STATUTS)]
    private string $statut = self::STATUT_DEPOSE;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $deposeLe = null;

    #[ORM\ManyToOne(targetEntity: Eleve::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Eleve $eleve = null;

    #[ORM\OneToMany(targetEntity: FichierJustificatif::class, mappedBy: 'justificatif', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $fichiers;

    public function __construct()
    {
        $this->deposeLe = new \DateTimeImmutable();
        $this->fichiers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): static
    {
        $this->source = $source;

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

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getDeposeLe(): ?\DateTimeImmutable
    {
        return $this->deposeLe;
    }

    public function setDeposeLe(\DateTimeImmutable $deposeLe): static
    {
        $this->deposeLe = $deposeLe;

        return $this;
    }

    public function getEleve(): ?Eleve
    {
        return $this->eleve;
    }

    public function setEleve(Eleve $eleve): static
    {
        $this->eleve = $eleve;

        return $this;
    }

    /**
     * @return Collection<int, FichierJustificatif>
     */
    public function getFichiers(): Collection
    {
        return $this->fichiers;
    }

    public function addFichier(FichierJustificatif $fichier): static
    {
        if (!$this->fichiers->contains($fichier)) {
            $this->fichiers->add($fichier);
            $fichier->setJustificatif($this);
        }

        return $this;
    }

    public function removeFichier(FichierJustificatif $fichier): static
    {
        $this->fichiers->removeElement($fichier);

        return $this;
    }
}
