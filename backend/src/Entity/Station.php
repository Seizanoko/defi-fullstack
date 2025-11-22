<?php

namespace App\Entity;

use App\Repository\StationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a network station (node).
 *
 * Properties:
 * - shortName : short unique identifier (max 10 characters).
 * - longName  : full human-readable name.
 */
#[ORM\Entity(repositoryClass: StationRepository::class)]
class Station
{
    /**
     * Primary auto-generated identifier.
     *
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Short unique identifier for the station.
     *
     * @var string|null
     */
    #[ORM\Column(length: 10, unique: true)]
    private ?string $shortName = null;

    /**
     * Full name of the station.
     *
     * @var string|null
     */
    #[ORM\Column(length: 100)]
    private ?string $longName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(string $shortName): static
    {
        $this->shortName = $shortName;

        return $this;
    }

    public function getLongName(): ?string
    {
        return $this->longName;
    }

    public function setLongName(string $longName): static
    {
        $this->longName = $longName;

        return $this;
    }
}
