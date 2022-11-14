<?php

namespace App\Entity;

use App\Repository\GenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GenreRepository::class)]
class Genre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'sudGenre')]
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private Collection $sudGenre;

    #[ORM\ManyToMany(targetEntity: Music::class, mappedBy: 'genre')]
    private Collection $music;

    public function __construct()
    {
        $this->sudGenre = new ArrayCollection();
        $this->music = new ArrayCollection();
    }
    
    /**
     * @return string
     */
    public function __toString() 
    {
        return $this->label;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getSudGenre(): Collection
    {
        return $this->sudGenre;
    }

    public function addSudGenre(self $sudGenre): self
    {
        if (!$this->sudGenre->contains($sudGenre)) {
            $this->sudGenre->add($sudGenre);
            $sudGenre->setParent($this);
        }

        return $this;
    }

    public function removeSudGenre(self $sudGenre): self
    {
        if ($this->sudGenre->removeElement($sudGenre)) {
            // set the owning side to null (unless already changed)
            if ($sudGenre->getParent() === $this) {
                $sudGenre->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Music>
     */
    public function getMusic(): Collection
    {
        return $this->music;
    }

    public function addMusic(Music $music): self
    {
        if (!$this->music->contains($music)) {
            $this->music->add($music);
            $music->addGenre($this);
        }

        return $this;
    }

    public function removeMusic(Music $music): self
    {
        if ($this->music->removeElement($music)) {
            $music->removeGenre($this);
        }

        return $this;
    }
}
