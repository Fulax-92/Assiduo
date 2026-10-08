<?php

namespace App\Entity;

use App\Enum\SourceJustificatif;
use App\Enum\StatutJustificatif;
use App\Repository\JustificatifRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: JustificatifRepository::class)]
class Justificatif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotNull]
    #[ORM\Column(enumType: SourceJustificatif::class, length: 20)]
    private ?SourceJustificatif $source = null;

    #[ORM\Column(enumType: StatutJustificatif::class, length: 20)]
    private StatutJustificatif $statut = StatutJustificatif::Depose;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $deposeLe = null;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Eleve::class, inversedBy: 'justificatifs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Eleve $eleve = null;

    /**
     * @var Collection<int, FichierJustificatif>
     */
    #[ORM\OneToMany(targetEntity: FichierJustificatif::class, mappedBy: 'justificatif', cascade: ['persist'], orphanRemoval: true)]
    private Collection $fichiers;

    /**
     * @var Collection<int, TraitementAbsence>
     */
    #[ORM\ManyToMany(targetEntity: TraitementAbsence::class, mappedBy: 'justificatifs')]
    private Collection $traitements;

    public function __construct()
    {
        $this->deposeLe = new \DateTimeImmutable();
        $this->fichiers = new ArrayCollection();
        $this->traitements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSource(): ?SourceJustificatif
    {
        return $this->source;
    }

    public function setSource(SourceJustificatif $source): static
    {
        $this->source = $source;

        return $this;
    }

    public function getStatut(): StatutJustificatif
    {
        return $this->statut;
    }

    public function setStatut(StatutJustificatif $statut): static
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

    public function setEleve(?Eleve $eleve): static
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
        if ($this->fichiers->removeElement($fichier)) {
            // set the owning side to null (unless already changed)
            if ($fichier->getJustificatif() === $this) {
                $fichier->setJustificatif(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TraitementAbsence>
     */
    public function getTraitements(): Collection
    {
        return $this->traitements;
    }

    public function addTraitement(TraitementAbsence $traitement): static
    {
        if (!$this->traitements->contains($traitement)) {
            $this->traitements->add($traitement);
            $traitement->addJustificatif($this);
        }

        return $this;
    }

    public function removeTraitement(TraitementAbsence $traitement): static
    {
        if ($this->traitements->removeElement($traitement)) {
            $traitement->removeJustificatif($this);
        }

        return $this;
    }
}
