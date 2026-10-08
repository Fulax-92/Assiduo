<?php

namespace App\Entity;

use App\Enum\DecisionTraitement;
use App\Repository\TraitementAbsenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TraitementAbsenceRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['courantCle'], message: 'Cette absence a déjà un traitement courant.', errorPath: 'estCourant')]
class TraitementAbsence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: DecisionTraitement::class, length: 20)]
    private DecisionTraitement $decision = DecisionTraitement::EnAttente;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $traiteLe = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $estCourant = true;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Presence::class, inversedBy: 'traitements')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Presence $presence = null;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $traitePar = null;

    #[ORM\ManyToOne(targetEntity: Motif::class)]
    private ?Motif $motif = null;

    /**
     * @var Collection<int, Justificatif>
     */
    #[ORM\ManyToMany(targetEntity: Justificatif::class, inversedBy: 'traitements')]
    #[ORM\JoinTable(name: 'traitement_justificatif')]
    private Collection $justificatifs;

    /**
     * Vaut l'id de la présence quand estCourant = true, NULL sinon.
     * L'index UNIQUE (MySQL accepte plusieurs NULL) garantit
     * un seul traitement courant par présence.
     */
    #[ORM\Column(nullable: true, unique: true)]
    private ?int $courantCle = null;

    public function __construct()
    {
        $this->traiteLe = new \DateTimeImmutable();
        $this->justificatifs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDecision(): DecisionTraitement
    {
        return $this->decision;
    }

    public function setDecision(DecisionTraitement $decision): static
    {
        $this->decision = $decision;

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

    public function getTraiteLe(): ?\DateTimeImmutable
    {
        return $this->traiteLe;
    }

    public function setTraiteLe(\DateTimeImmutable $traiteLe): static
    {
        $this->traiteLe = $traiteLe;

        return $this;
    }

    public function estCourant(): bool
    {
        return $this->estCourant;
    }

    public function setEstCourant(bool $estCourant): static
    {
        $this->estCourant = $estCourant;
        $this->recalculerCourantCle();

        return $this;
    }

    public function getPresence(): ?Presence
    {
        return $this->presence;
    }

    public function setPresence(?Presence $presence): static
    {
        $this->presence = $presence;
        $this->recalculerCourantCle();

        return $this;
    }

    public function getTraitePar(): ?Utilisateur
    {
        return $this->traitePar;
    }

    public function setTraitePar(?Utilisateur $traitePar): static
    {
        $this->traitePar = $traitePar;

        return $this;
    }

    public function getMotif(): ?Motif
    {
        return $this->motif;
    }

    public function setMotif(?Motif $motif): static
    {
        $this->motif = $motif;

        return $this;
    }

    /**
     * @return Collection<int, Justificatif>
     */
    public function getJustificatifs(): Collection
    {
        return $this->justificatifs;
    }

    public function addJustificatif(Justificatif $justificatif): static
    {
        if (!$this->justificatifs->contains($justificatif)) {
            $this->justificatifs->add($justificatif);
        }

        return $this;
    }

    public function removeJustificatif(Justificatif $justificatif): static
    {
        $this->justificatifs->removeElement($justificatif);

        return $this;
    }

    public function getCourantCle(): ?int
    {
        return $this->courantCle;
    }

    /**
     * Rattrapage à l'insertion, pour une présence qui n'avait pas encore d'id
     * au moment du setPresence(). En mise à jour, les setters suffisent
     * (Doctrine ignore les champs modifiés dans un PreUpdate).
     */
    #[ORM\PrePersist]
    public function recalculerCourantCle(): void
    {
        $this->courantCle = $this->estCourant ? $this->presence?->getId() : null;
    }
}
