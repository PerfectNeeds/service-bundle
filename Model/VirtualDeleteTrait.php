<?php

namespace PN\ServiceBundle\Model;

trait VirtualDeleteTrait
{

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deleted = null;

    /**
     * @ORM\Column(name="deleted_by", type="string", length=255, nullable=true)
     */
    private $deletedBy = null;


    public function setDeleted(\DateTimeInterface $deleted): static
    {
        $this->deleted = $deleted;
        if (method_exists($this, 'getSeo') and $this->getSeo() != null) {
            $this->getSeo()->setDeleted(true);
            $rand = substr(md5(microtime()), rand(0, 26), 5);
            $this->getSeo()->SetSlug($this->getSeo()->getSlug().'-del-'.$rand);
        }

        return $this;
    }

    public function getDeleted(): ?\DateTimeInterface
    {
        return $this->deleted;
    }

    public function isDeleted(): bool
    {
        if ($this->deleted instanceof \DateTimeInterface) {
            return true;
        }

        return false;
    }

    public function setDeletedBy(string $deletedBy): static
    {
        $this->deletedBy = $deletedBy;

        return $this;
    }

    public function getDeletedBy(): ?string
    {
        return $this->deletedBy;
    }

}
