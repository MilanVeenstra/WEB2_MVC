<?php
namespace Framework\Database;

class Query implements QueryInterface
{
    /** @var array<string,mixed> */
    private array $conditions = [];

    public function where(string $field, mixed $value): static
    {
        $this->conditions[$field] = $value;
        return $this;
    }

    public function getConditions(): array
    {
        return $this->conditions;
    }
}
