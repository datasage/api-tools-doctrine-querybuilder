<?php // phpcs:disable

namespace Db\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;

class Artist
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $name;

    /** @var DateTime */
    protected $createdAt;

    /** @var ArrayCollection<int, Album> */
    protected $album;

    public function __construct()
    {
        $this->album = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @return static
     */
    public function setName(string $value): self
    {
        $this->name = $value;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return static
     */
    public function setCreatedAt(DateTime $value): self
    {
        $this->createdAt = $value;

        return $this;
    }

    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * Add album
     *
     * @param Album|ArrayCollection<array-key, Album> $album
     * @return Artist
     * @throws Exception
     */
    public function addAlbum($album)
    {
        if ($album instanceof Album) {
            $this->album[] = $album;
        } else {
            foreach ($album as $a) {
                if (! $a instanceof Album) {
                    throw new Exception('Invalid type in addAlbum');
                }
                $this->album->add($a);
            }
        }

        return $this;
    }

    /**
     * Remove album
     *
     * @param Album|ArrayCollection<array-key, Album> $album
     *
     * @throws Exception
     *
     * @return void
     */
    public function removeAlbum($album): void
    {
        if(is_iterable($album)) {
            foreach($album as $a) {
                $this->album->removeElement($a);
            }
        } elseif ($album instanceof Album) {
            $this->album->removeElement($album);
        }
    }
}
