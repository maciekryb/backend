<?php

namespace App\Entity;

use App\Repository\ConferenceRoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConferenceRoomRepository::class)]
class ConferenceRoom
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Name is required.")]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: "Name must be at least {{ limit }} characters long.",
        maxMessage: "Name cannot be longer than {{ limit }} characters."
    )]
    private ?string $name = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Capacity is required.")]
    #[Assert\Type(type: "numeric", message: "Capacity must be a number.")]
    #[Assert\GreaterThan(value: 0, message: "Capacity must be greater than 0.")]
    private ?int $capacity = null;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'conferenceRoom')]
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCapacity()
    {
        return $this->capacity;
    }

    public function setCapacity($capacity): static
    {
        $this->capacity = $capacity;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setConferenceRoom($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getConferenceRoom() === $this) {
                $reservation->setConferenceRoom(null);
            }
        }

        return $this;
    }
}
