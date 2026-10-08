<?php

namespace App\Entity;

use App\Enum\ActionAudit;
use App\Repository\JournalAuditRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: JournalAuditRepository::class)]
#[ORM\Index(name: 'idx_audit_entite', columns: ['entite_type', 'entite_id'])]
#[ORM\Index(name: 'idx_audit_horodate', columns: ['horodate'])]
class JournalAudit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $horodate = null;

    #[Assert\NotNull]
    #[ORM\Column(enumType: ActionAudit::class, length: 20)]
    private ?ActionAudit $action = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    #[ORM\Column(type: Types::STRING, length: 100)]
    private ?string $entiteType = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $entiteId = null;

    #[Assert\Ip(version: Assert\Ip::ALL)]
    #[ORM\Column(type: Types::STRING, length: 45, nullable: true)]
    private ?string $adresseIp = null;

    #[Assert\Length(max: 512)]
    #[ORM\Column(type: Types::STRING, length: 512, nullable: true)]
    private ?string $userAgent = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $details = null;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: false)]
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

    public function getAction(): ?ActionAudit
    {
        return $this->action;
    }

    public function setAction(ActionAudit $action): static
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

    public function setEntiteId(?int $entiteId): static
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

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }
}
