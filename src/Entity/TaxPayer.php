<?php declare(strict_types=1);

namespace App\Entity;

use App\InnNumber\InnNumberInterface;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaxPayerRepository")
 * @ORM\HasLifecycleCallbacks
 */
class TaxPayer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;
    /**
     * @ORM\Column(type="innNumber", unique=true)
     */
    private InnNumberInterface $inn;
    /**
     * @ORM\Column(type="integer", length=1)
     */
    private bool $pays;
    /**
     * @ORM\Column(type="datetime", columnDefinition="DATETIME on update CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP")
     */
    private DateTimeInterface $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInn(): InnNumberInterface
    {
        return $this->inn;
    }

    public function setInn(InnNumberInterface $inn): void
    {
        $this->inn = $inn;
    }

    public function isPays(): bool
    {
        return $this->pays;
    }

    public function setPays(bool $pays): void
    {
        $this->pays = $pays;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersist(): void
    {
        $this->updatedAt = new DateTime();
    }
}
