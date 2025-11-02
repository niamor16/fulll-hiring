<?php

namespace Fulll\Domain\Model\Shared;

final class UniqId
{
    private string $id;

    private function __construct(string $id)
    {
        $this->id = $id;
    }

    public function __toString(): string
    {
        return $this->id;
    }

    public function equals(UniqId $id): bool
    {
        return $this->id === $id->id;
    }

    static public function fromString(string $id): self
    {
        return new self($id);
    }

    static public function new(): self
    {
        return new self(uniqid('', true));
    }

    public function getId(): string
    {
        return $this->id;
    }
}