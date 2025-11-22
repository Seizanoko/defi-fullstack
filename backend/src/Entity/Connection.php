<?php

namespace App\Entity;

use App\Repository\ConnectionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a connection between two stations.
 *
 * Fields:
 * - parentStation : source station (ManyToOne to Station).
 * - childStation  : destination station (ManyToOne to Station).
 * - distance      : distance between stations in kilometer (float).
 * - networkName   : name of the network this connection belongs to.
 */
#[ORM\Entity(repositoryClass: ConnectionRepository::class)]
class Connection
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
     * Parent (source) station.
     *
     * @var Station|null
     */
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Station $parentStation = null;

    /**
     * Child (destination) station.
     *
     * @var Station|null
     */
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Station $childStation = null;

    /**
     * Distance between the two stations in kilometer.
     *
     * @var float|null
     */
    #[ORM\Column]
    private ?float $distance = null;

    /**
     * Network or line name this connection belongs to.
     *
     * @var string|null
     */
    #[ORM\Column(length: 50)]
    private ?string $networkName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParentStation(): ?Station
    {
        return $this->parentStation;
    }

    public function setParentStation(?Station $parentStation): static
    {
        $this->parentStation = $parentStation;

        return $this;
    }

    public function getChildStation(): ?Station
    {
        return $this->childStation;
    }

    public function setChildStation(?Station $childStation): static
    {
        $this->childStation = $childStation;

        return $this;
    }

    public function getDistance(): ?float
    {
        return $this->distance;
    }

    public function setDistance(float $distance): static
    {
        $this->distance = $distance;

        return $this;
    }

    public function getNetworkName(): ?string
    {
        return $this->networkName;
    }

    public function setNetworkName(string $networkName): static
    {
        $this->networkName = $networkName;

        return $this;
    }
}
