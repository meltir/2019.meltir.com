<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity(repositoryClass="App\Repository\YtCategoriesRepository")
 */
class YtCategories
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\YtChannels", mappedBy="category")
     */
    private $ytChannels;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Gedmo\Slug(fields={"name","id"})
     */
    private $slug;

    /**
     * @ORM\Column(type="integer")
     * @Gedmo\SortablePosition
     */
    private $cat_order;

    public function __construct()
    {
        $this->ytChannels = new ArrayCollection();
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

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection|YtChannels[]
     */
    public function getYtChannels(): Collection
    {
        return $this->ytChannels;
    }

    public function addYtChannel(YtChannels $ytChannel): self
    {
        if (!$this->ytChannels->contains($ytChannel)) {
            $this->ytChannels[] = $ytChannel;
            $ytChannel->setCategory($this);
        }

        return $this;
    }

    public function removeYtChannel(YtChannels $ytChannel): self
    {
        if ($this->ytChannels->contains($ytChannel)) {
            $this->ytChannels->removeElement($ytChannel);
            // set the owning side to null (unless already changed)
            if ($ytChannel->getCategory() === $this) {
                $ytChannel->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
        // TODO: Implement __toString() method.
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCatOrder(): ?int
    {
        return $this->cat_order;
    }

    public function setCatOrder(int $cat_order): self
    {
        $this->cat_order = $cat_order;

        return $this;
    }

}
