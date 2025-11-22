<?php

namespace App\Entity;

use App\Repository\ConnectionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a connection between two stations.
 *
 * Fields:
 * - parentStation : source station.
 * - childStation  : destination station.
 * - distance      : distance between stations in kilometer (float).
 * - networkName   : name of the network this connection belongs to.
 */
#[ORM\Entity(repositoryClass: ConnectionRepository::class)]
#[ORM\Index(name: "idx_parent_station", columns: ["parent_station_id"])]
#[ORM\Index(name: "idx_child_station", columns: ["child_station_id"])]
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
     * @var string|null
     */
    #[ORM\Column(length: 10)]
    private ?string $parentStation = null;

    /**
     * Child (destination) station.
     *
     * @var string|null
     */
    #[ORM\Column(length: 10)]
    private ?string $childStation = null;

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

    public function getParentStation(): ?string
    {
        return $this->parentStation;
    }

    public function setParentStation(string $parentStation): static
    {
        $this->parentStation = $parentStation;

        return $this;
    }

    public function getChildStation(): ?string
    {
        return $this->childStation;
    }

    public function setChildStation(string $childStation): static
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
