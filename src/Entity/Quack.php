<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuackRepository")
 */
class Quack
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DuckDuck", inversedBy="quacks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @Assert\Image(mimeTypes={ "image/jpeg", "image/png", "image/bpm", "image/gif"} )
     */
    private $imageFile;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Quack", inversedBy="children")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Quack", mappedBy="parent", orphanRemoval=true)
     */
    private $children;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $tags = [];

    /**
     * @return mixed
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param mixed $imageFile
     */
    public function setImageFile($imageFile): void
    {
        $this->imageFile = $imageFile;
    }


    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }


    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }


    public function getAuthor(): ?duckduck
    {
        return $this->author;
    }

    public function setAuthor(?duckduck $author): self
    {
        $this->author = $author;

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
     * @return Collection|self[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): self
    {
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    public function getTags(): ?array
    {
        return $this->tags ?? [];
    }

    public function setTags(?array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'created_at' => $this->createdAt,
            'auteur' => $this->author,
            'photo' => $this->image,
            'tag' => $this->tags,
            'children' => $this->children,];
    }

    public function monTableauLight()
    {
        return [
            "id" => $this->id,
            "content" => $this->content,
            "photo" => $this->image,
        ];
    }

}
