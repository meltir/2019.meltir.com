<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PagePostRepository")
 * @vich\Uploadable
 */
class PagePost
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
    private $body;

    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(type="integer", nullable=true)
     */
    private $post_order;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $active;

    /**
     * @Gedmo\SortableGroup
     * @ORM\Column(type="string", length=255)
     */
    private $page;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $link_image;

    /**
     * @Vich\UploadableField(mapping="post_images", fileNameProperty="link_image")
     * @var File
     */
    private $link_image_file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $link_url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $link_title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var PagePost[]|null
     */
    public $items = [];

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PagePost", inversedBy="pagePosts")
     */
    private $parent_post;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PagePost", mappedBy="parent_post")
     */
    private $pagePosts;

    public function __construct()
    {
        $this->pagePosts = new ArrayCollection();
    }

    public function setLinkImageFile(File $image = null) {
        $this->link_image_file = $image;
    }

    public function getLinkImageFile() {
        return $this->link_image_file;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getPostOrder(): ?int
    {
        return $this->post_order;
    }

    public function setPostOrder(?int $post_order): self
    {
        $this->post_order = $post_order;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getPage(): ?string
    {
        return $this->page;
    }

    public function setPage(string $page): self
    {
        $this->page = $page;

        return $this;
    }


    public function getLinkImage(): ?string
    {
        return $this->link_image;
    }

    public function setLinkImage(?string $link_image): self
    {
        $this->link_image = $link_image;

        return $this;
    }

    public function getLinkUrl(): ?string
    {
        return $this->link_url;
    }

    public function setLinkUrl(?string $link_url): self
    {
        $this->link_url = $link_url;

        return $this;
    }

    public function getLinkTitle(): ?string
    {
        return $this->link_title;
    }

    public function setLinkTitle(?string $link_title): self
    {
        $this->link_title = $link_title;

        return $this;
    }

    public function getTitle(): ?string
    {
        if (empty($this->title)) {
            return substr($this->body,0,10);
        }
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function __toString()
    {
        return $this->getTitle();
        // TODO: Implement __toString() method.
    }

    public function getParentPost(): ?self
    {
        return $this->parent_post;
    }

    public function setParentPost(?self $parent_post): self
    {
        $this->parent_post = $parent_post;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getPagePosts(): Collection
    {
        return $this->pagePosts;
    }

    public function addPagePost(self $pagePost): self
    {
        if (!$this->pagePosts->contains($pagePost)) {
            $this->pagePosts[] = $pagePost;
            $pagePost->setParentPost($this);
        }

        return $this;
    }

    public function removePagePost(self $pagePost): self
    {
        if ($this->pagePosts->contains($pagePost)) {
            $this->pagePosts->removeElement($pagePost);
            // set the owning side to null (unless already changed)
            if ($pagePost->getParentPost() === $this) {
                $pagePost->setParentPost(null);
            }
        }

        return $this;
    }

}
