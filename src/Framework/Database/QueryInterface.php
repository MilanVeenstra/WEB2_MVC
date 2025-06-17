<?php
namespace Framework\Database;

/**
 * QueryInterface voor dynamische zoekcriteria.
 */
interface QueryInterface
{
    /**
     * Voeg een WHERE-voorwaarde toe.
     *
     * @param string $field
     * @param mixed  $value
     * @return static
     */
    public function where(string $field, mixed $value): static;

    /**
     * Haal alle voorwaarden op als associatieve array ['field' => value, …].
     *
     * @return array<string,mixed>
     */
    public function getConditions(): array;
}
