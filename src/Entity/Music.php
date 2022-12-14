<?php

namespace App\Entity;

use App\Repository\MusicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MusicRepository::class)]
class Music
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $year = null;

    #[ORM\ManyToOne(inversedBy: 'musics')]
    private ?Album $album = null;

    #[ORM\ManyToMany(targetEntity: Playlist::class, mappedBy: 'musics')]
    private Collection $playlists;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'musics')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Artist $artist = null;

    #[ORM\ManyToMany(targetEntity: Genre::class, inversedBy: 'music')]
    private Collection $genres;

    #[ORM\ManyToMany(targetEntity: SharedPlaylist::class, mappedBy: 'musics')]
    private Collection $sharedPlaylists;

    public function __construct()
    {
        $this->playlists = new ArrayCollection();
        $this->genres = new ArrayCollection();
        $this->sharedPlaylists = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString() 
    {
        return $this->title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getAlbum(): ?Album
    {
        return $this->album;
    }

    public function setAlbum(?Album $album): self
    {
        $this->album = $album;

        return $this;
    }

    /**
     * @return Collection<int, Playlist>
     */
    public function getPlaylists(): Collection
    {
        return $this->playlists;
    }

    public function addPlaylist(Playlist $playlist): self
    {
        if (!$this->playlists->contains($playlist)) {
            $this->playlists->add($playlist);
            $playlist->addMusic($this);
        }

        return $this;
    }

    public function removePlaylist(Playlist $playlist): self
    {
        if ($this->playlists->removeElement($playlist)) {
            $playlist->removeMusic($this);
        }

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    public function setArtist(?Artist $artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres->add($genre);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        $this->genres->removeElement($genre);

        return $this;
    }

    /**
     * @return Collection<int, SharedPlaylist>
     */
    public function getSharedPlaylists(): Collection
    {
        return $this->sharedPlaylists;
    }

    public function addSharedPlaylist(SharedPlaylist $sharedPlaylist): self
    {
        if (!$this->sharedPlaylists->contains($sharedPlaylist)) {
            $this->sharedPlaylists->add($sharedPlaylist);
            $sharedPlaylist->addMusic($this);
        }

        return $this;
    }

    public function removeSharedPlaylist(SharedPlaylist $sharedPlaylist): self
    {
        if ($this->sharedPlaylists->removeElement($sharedPlaylist)) {
            $sharedPlaylist->removeMusic($this);
        }

        return $this;
    }
}
