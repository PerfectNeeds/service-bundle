<?php

namespace PN\ServiceBundle\Model;

trait VirtualDeleteTrait
{

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $deleted = null;

    /**
     * @ORM\Column(name="deleted_by", type="string", length=255, nullable=true)
     */
    protected $deletedBy = null;

    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
        if (method_exists($this, 'getSeo') == true and $this->getSeo() != null) {
            $this->getSeo()->setDeleted(true);
            $rand = substr(md5(microtime()), rand(0, 26), 5);
            $this->getSeo()->SetSlug($this->getSeo()->getSlug().'-del-'.$rand);
        }

        return $this;
    }

    public function getDeleted()
    {
        return $this->deleted;
    }

    public function isDeleted()
    {
        if ($this->deleted instanceof \DateTimeInterface) {
            return true;
        }

        return false;
    }

    public function setDeletedBy($deletedBy)
    {
        $this->deletedBy = $deletedBy;

        return $this;
    }

    public function getDeletedBy()
    {
        return $this->deletedBy;
    }

}
