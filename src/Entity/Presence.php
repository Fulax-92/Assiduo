<?php

namespace App\Entity;

use App\Enum\StatutPresence;
use App\Repository\PresenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: PresenceRepository::class)]
#[ORM\UniqueConstraint(name: 'uniq_presence_appel_inscription', columns: ['appel_id', 'inscription_id'])]
#[UniqueEntity(fields: ['appel', 'inscription'], message: 'Cet élève a déjà une ligne de présence pour cet appel.')]
class Presence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotNull]
    #[ORM\Column(enumType: StatutPresence::class, length: 20)]
    private ?StatutPresence $statut = null;

    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true, 'default' => 0])]
    private int $minutesRetard = 0;

    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true, 'default' => 0])]
    private int $minutesAbsence = 0;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $saisiLe = null;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Appel::class, inversedBy: 'presences')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Appel $appel = null;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Inscription::class, inversedBy: 'presences')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Inscription $inscription = null;

    /**
     * @var Collection<int, TraitementAbsence>
     */
    #[ORM\OneToMany(targetEntity: TraitementAbsence::class, mappedBy: 'presence')]
    #[ORM\OrderBy(['traiteLe' => 'DESC'])]
    private Collection $traitements;

    public function __construct()
    {
        $this->saisiLe = new \DateTimeImmutable();
        $this->traitements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatut(): ?StatutPresence
    {
        return $this->statut;
    }

    public function setStatut(StatutPresence $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getMinutesRetard(): int
    {
        return $this->minutesRetard;
    }

    public function setMinutesRetard(int $minutesRetard): static
    {
        $this->minutesRetard = $minutesRetard;

        return $this;
    }

    public function getMinutesAbsence(): int
    {
        return $this->minutesAbsence;
    }

    public function setMinutesAbsence(int $minutesAbsence): static
    {
        $this->minutesAbsence = $minutesAbsence;

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

    public function getSaisiLe(): ?\DateTimeImmutable
    {
        return $this->saisiLe;
    }

    public function setSaisiLe(\DateTimeImmutable $saisiLe): static
    {
        $this->saisiLe = $saisiLe;

        return $this;
    }

    public function getAppel(): ?Appel
    {
        return $this->appel;
    }

    public function setAppel(?Appel $appel): static
    {
        $this->appel = $appel;

        return $this;
    }

    public function getInscription(): ?Inscription
    {
        return $this->inscription;
    }

    public function setInscription(?Inscription $inscription): static
    {
        $this->inscription = $inscription;

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
            $traitement->setPresence($this);
        }

        return $this;
    }

    public function removeTraitement(TraitementAbsence $traitement): static
    {
        if ($this->traitements->removeElement($traitement)) {
            // set the owning side to null (unless already changed)
            if ($traitement->getPresence() === $this) {
                $traitement->setPresence(null);
            }
        }

        return $this;
    }

    #[Assert\Callback]
    public function validerMinutes(ExecutionContextInterface $context): void
    {
        if (StatutPresence::Retard !== $this->statut && $this->minutesRetard > 0) {
            $context->buildViolation('Les minutes de retard ne concernent que le statut RETARD.')
                ->atPath('minutesRetard')
                ->addViolation();
        }

        if (StatutPresence::Retard === $this->statut && 0 === $this->minutesRetard) {
            $context->buildViolation('Indique le nombre de minutes de retard.')
                ->atPath('minutesRetard')
                ->addViolation();
        }
    }

    public function getTraitementCourant(): ?TraitementAbsence
    {
        foreach ($this->traitements as $traitement) {
            if ($traitement->estCourant()) {
                return $traitement;
            }
        }

        return null;
    }
}
