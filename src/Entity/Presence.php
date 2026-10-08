<?php

namespace App\Entity;

use App\Repository\PresenceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: PresenceRepository::class)]
#[ORM\Table(name: 'presence')]
#[ORM\UniqueConstraint(name: 'uniq_presence_appel_inscription', columns: ['appel_id', 'inscription_id'])]
#[UniqueEntity(fields: ['appel', 'inscription'], errorPath: 'appel', message: 'Cet élève a déjà une présence saisie pour cet appel.')]
class Presence
{
    public const STATUT_PRESENT = 'PRESENT';
    public const STATUT_ABSENT = 'ABSENT';
    public const STATUT_RETARD = 'RETARD';
    public const STATUT_EXCUSE = 'EXCUSE';
    public const STATUTS = [self::STATUT_PRESENT, self::STATUT_ABSENT, self::STATUT_RETARD, self::STATUT_EXCUSE];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30, options: ['default' => self::STATUT_PRESENT])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 30)]
    #[Assert\Choice(choices: self::STATUTS)]
    private string $statut = self::STATUT_PRESENT;

    #[ORM\Column(options: ['default' => 0])]
    #[Assert\PositiveOrZero]
    private int $minutesRetard = 0;

    #[ORM\Column(options: ['default' => 0])]
    #[Assert\PositiveOrZero]
    private int $minutesAbsence = 0;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $saisiLe = null;

    #[ORM\ManyToOne(targetEntity: Appel::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Appel $appel = null;

    #[ORM\ManyToOne(targetEntity: Inscription::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Inscription $inscription = null;

    public function __construct()
    {
        $this->saisiLe = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function setAppel(Appel $appel): static
    {
        $this->appel = $appel;

        return $this;
    }

    public function getInscription(): ?Inscription
    {
        return $this->inscription;
    }

    public function setInscription(Inscription $inscription): static
    {
        $this->inscription = $inscription;

        return $this;
    }

    #[Assert\Callback]
    public function validateCoherence(ExecutionContextInterface $context): void
    {
        if ($this->statut === self::STATUT_RETARD && $this->minutesRetard <= 0) {
            $context->buildViolation('Un retard doit durer au moins une minute.')->atPath('minutesRetard')->addViolation();
        }
    }
}
