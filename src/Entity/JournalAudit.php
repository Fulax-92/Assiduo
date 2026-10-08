<?php

namespace App\Entity;

use App\Repository\JournalAuditRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: JournalAuditRepository::class)]
#[ORM\Table(name: 'journal_audit')]
#[ORM\Index(name: 'idx_audit_entite', columns: ['entite_type', 'entite_id'])]
#[ORM\Index(name: 'idx_audit_horodate', columns: ['horodate'])]
class JournalAudit
{
    public const ACTION_CREATION = 'CREATION';
    public const ACTION_LECTURE = 'LECTURE';
    public const ACTION_MODIFICATION = 'MODIFICATION';
    public const ACTION_SUPPRESSION = 'SUPPRESSION';
    public const ACTION_CONNEXION = 'CONNEXION';
    public const ACTIONS = [self::ACTION_CREATION, self::ACTION_LECTURE, self::ACTION_MODIFICATION, self::ACTION_SUPPRESSION, self::ACTION_CONNEXION];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $horodate = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 30)]
    #[Assert\Choice(choices: self::ACTIONS)]
    private ?string $action = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    private ?string $entiteType = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Positive]
    private ?int $entiteId = null;

    #[ORM\Column(length: 45, nullable: true)]
    #[Assert\Length(max: 45)]
    #[Assert\Ip]
    private ?string $adresseIp = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    private ?string $userAgent = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $details = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Utilisateur $utilisateur = null;

    public function __construct()
    {
        $this->horodate = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHorodate(): ?\DateTimeImmutable
    {
        return $this->horodate;
    }

    public function setHorodate(\DateTimeImmutable $horodate): static
    {
        $this->horodate = $horodate;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): static
    {
        $this->action = $action;

        return $this;
    }

    public function getEntiteType(): ?string
    {
        return $this->entiteType;
    }

    public function setEntiteType(string $entiteType): static
    {
        $this->entiteType = $entiteType;

        return $this;
    }

    public function getEntiteId(): ?int
    {
        return $this->entiteId;
    }

    public function setEntiteId(int $entiteId): static
    {
        $this->entiteId = $entiteId;

        return $this;
    }

    public function getAdresseIp(): ?string
    {
        return $this->adresseIp;
    }

    public function setAdresseIp(?string $adresseIp): static
    {
        $this->adresseIp = $adresseIp;

        return $this;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(?string $userAgent): static
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): static
    {
        $this->details = $details;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }
}
