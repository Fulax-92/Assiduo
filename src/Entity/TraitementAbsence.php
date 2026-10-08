<?php

namespace App\Entity;

use App\Repository\TraitementAbsenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TraitementAbsenceRepository::class)]
#[ORM\Table(name: 'traitement_absence')]
#[ORM\UniqueConstraint(name: 'uniq_traitement_courant_presence', columns: ['presence_id', 'courant_unique'])]
class TraitementAbsence
{
    public const DECISION_EN_ATTENTE = 'EN_ATTENTE';
    public const DECISION_JUSTIFIEE = 'JUSTIFIEE';
    public const DECISION_REFUSEE = 'REFUSEE';
    public const DECISION_SANS_SUITE = 'SANS_SUITE';
    public const DECISIONS = [self::DECISION_EN_ATTENTE, self::DECISION_JUSTIFIEE, self::DECISION_REFUSEE, self::DECISION_SANS_SUITE];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30, options: ['default' => self::DECISION_EN_ATTENTE])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 30)]
    #[Assert\Choice(choices: self::DECISIONS)]
    private string $decision = self::DECISION_EN_ATTENTE;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $traiteLe = null;

    #[ORM\Column(options: ['default' => false])]
    private bool $estCourant = false;

    #[ORM\ManyToOne(targetEntity: Presence::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Presence $presence = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Utilisateur $traitePar = null;

    #[ORM\ManyToOne(targetEntity: Motif::class)]
    private ?Motif $motif = null;

    /**
     * Colonne technique : vaut 1 quand le traitement est courant, NULL sinon.
     * Combinée à presence_id dans une contrainte UNIQUE, elle garantit « un seul traitement
     * courant par présence » (MySQL autorise plusieurs NULL dans un index unique).
     */
    #[ORM\Column(name: 'courant_unique', type: Types::SMALLINT, nullable: true)]
    private ?int $courantUnique = null;

    #[ORM\ManyToMany(targetEntity: Justificatif::class)]
    #[ORM\JoinTable(name: 'traitement_justificatif')]
    #[ORM\JoinColumn(name: 'traitement_absence_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[ORM\InverseJoinColumn(name: 'justificatif_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Collection $justificatifs;

    public function __construct()
    {
        $this->traiteLe = new \DateTimeImmutable();
        $this->justificatifs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDecision(): string
    {
        return $this->decision;
    }

    public function setDecision(string $decision): static
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

    public function getPresence(): ?Presence
    {
        return $this->presence;
    }

    public function setPresence(Presence $presence): static
    {
        $this->presence = $presence;

        return $this;
    }

    public function getTraitePar(): ?Utilisateur
    {
        return $this->traitePar;
    }

    public function setTraitePar(Utilisateur $traitePar): static
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

    public function isCourant(): bool
    {
        return $this->estCourant;
    }

    public function setEstCourant(bool $estCourant): static
    {
        $this->estCourant = $estCourant;
        $this->courantUnique = $estCourant ? 1 : null;

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
}
