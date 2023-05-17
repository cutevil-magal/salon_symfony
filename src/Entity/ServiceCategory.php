<?php

namespace App\Entity;

use App\Repository\ServiceCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceCategoryRepository::class)]
class ServiceCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToOne(mappedBy: 'category', cascade: ['persist', 'remove'])]
    private ?Service $service = null;

    #[ORM\OneToMany(mappedBy: 'serviceCategory', targetEntity: Master::class)]
    private Collection $masters;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Service::class)]
    private Collection $services;

    public function __construct()
    {
        $this->masters = new ArrayCollection();
        $this->services = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Master>
     */
    public function getMasters(): Collection
    {
        return $this->masters;
    }

    public function addMaster(Master $master): self
    {
        if (!$this->masters->contains($master)) {
            $this->masters->add($master);
            $master->setServiceCategory($this);
        }

        return $this;
    }

    public function removeMaster(Master $master): self
    {
        if ($this->masters->removeElement($master)) {
            // set the owning side to null (unless already changed)
            if ($master->getServiceCategory() === $this) {
                $master->setServiceCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Service>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->setCategory($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getCategory() === $this) {
                $service->setCategory(null);
            }
        }

        return $this;
    }
}
