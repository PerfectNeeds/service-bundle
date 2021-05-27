<?php

namespace PN\ServiceBundle\Interfaces;

interface UUIDInterface
{
    public function getUuid(): ?string;

    public function setUuid(string $uuid);
}