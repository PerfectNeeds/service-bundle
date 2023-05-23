<?php

namespace PN\ServiceBundle\Model;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
trait DateTimeTrait
{

    /**
     * @ORM\Column(type="datetime")
     */
    #[ORM\Column(name: "created", type: Types::DATETIME_MUTABLE)]
    protected ?\DateTimeInterface $created = null;

    /**
     * @ORM\Column(name="creator", type="string", length=255)
     */
    #[ORM\Column(name: "creator", type: Types::STRING, length: 255)]
    protected ?string $creator = null;

    /**
     * @ORM\Column(type="datetime")
     */
    #[ORM\Column(name: "modified", type: Types::DATETIME_MUTABLE)]
    protected ?\DateTimeInterface $modified = null;

    /**
     * @ORM\Column(name="modified_by", type="string", length=255)
     */
    #[ORM\Column(name: "modified_by", type: Types::STRING, length: 255)]
    protected ?string $modifiedBy = null;


    public function setCreated($created): static
    {
        $this->created = $created;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreator($creator): static
    {
        $this->creator = $creator;

        return $this;
    }

    public function getCreator(): ?string
    {
        return $this->creator;
    }

    public function setModified($modified): static
    {
        $this->modified = $modified;

        return $this;
    }

    public function getModified(): ?\DateTimeInterface
    {
        return $this->modified;
    }

    public function setModifiedBy($modifiedBy): static
    {
        $this->modifiedBy = $modifiedBy;

        return $this;
    }

    public function getModifiedBy(): ?string
    {
        return $this->modifiedBy;
    }

}
