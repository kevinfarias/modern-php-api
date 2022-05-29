<?php

namespace Core\Domain\Entity\Traits;

trait MethodsMagicsTrait
{
    public function createdAt(): string
    {
        return $this->createdAt->format("Y-m-d H:i:s");
    }
    
    public function updatedAt(): string
    {
        return $this->updatedAt->format("Y-m-d H:i:s");
    }
}