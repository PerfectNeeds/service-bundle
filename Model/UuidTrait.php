<?php

namespace PN\ServiceBundle\Model;
use Doctrine\ORM\Mapping as ORM;
trait UuidTrait
{
    /**
     * @ORM\Column(name="uuid", type="string", length=50, unique=true)
     */
    protected $uuid;

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

}
