<?php

namespace App\Entity;

use App\Repository\RouteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents an analyzed route between two stations.
 *
 * Fields:
 * - fromStationId / toStationId : short identifiers of origin/destination stations.
 * - analyticCode                : analysis or classification code for the route.
 * - distanceKm                  : total distance in kilometers.
 * - path                        : detailed path (array structure; e.g. list of station IDs).
 * - createdAt                   : immutable creation timestamp.
 */
#[ORM\Entity(repositoryClass: RouteRepository::class)]
#[ORM\Index(name: "idx_analytic_code", columns: ["analytic_code"])]
#[ORM\Index(name: "idx_analytic_created", columns: ["analytic_code", "created_at"])]
#[ORM\Index(name: "idx_created_at", columns: ["created_at"])]
class Route
{
    /**
     * Primary identifier.
     *
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Short ID of the origin station.
     *
     * @var string|null
     */
    #[ORM\Column(length: 10)]
    private ?string $fromStationId = null;

    /**
     * Short ID of the destination station.
     *
     * @var string|null
     */
    #[ORM\Column(length: 10)]
    private ?string $toStationId = null;

    /**
     * Analytical code for this route.
     *
     * @var string|null
     */
    #[ORM\Column(length: 50)]
    private ?string $analyticCode = null;

    /**
     * Total distance in kilometers.
     *
     * @var float|null
     */
    #[ORM\Column]
    private ?float $distanceKm = null;

    /**
     * Path details as an array (structure depends on usage).
     *
     * @var array
     */
    #[ORM\Column]
    private array $path = [];

    /**
     * Creation timestamp (immutable).
     *
     * @var \DateTimeImmutable|null
     */
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFromStationId(): ?string
    {
        return $this->fromStationId;
    }

    public function setFromStationId(string $fromStationId): static
    {
        $this->fromStationId = $fromStationId;

        return $this;
    }

    public function getToStationId(): ?string
    {
        return $this->toStationId;
    }

    public function setToStationId(string $toStationId): static
    {
        $this->toStationId = $toStationId;

        return $this;
    }

    public function getAnalyticCode(): ?string
    {
        return $this->analyticCode;
    }

    public function setAnalyticCode(string $analyticCode): static
    {
        $this->analyticCode = $analyticCode;

        return $this;
    }

    public function getDistanceKm(): ?float
    {
        return $this->distanceKm;
    }

    public function setDistanceKm(float $distanceKm): static
    {
        $this->distanceKm = $distanceKm;

        return $this;
    }

    public function getPath(): array
    {
        return $this->path;
    }

    public function setPath(array $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
