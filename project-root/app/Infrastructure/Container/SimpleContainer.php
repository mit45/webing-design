<?php

namespace App\Infrastructure\Container;

class SimpleContainer
{
    private array $bindings = [];

    public function set(string $id, callable $resolver): void
    {
        $this->bindings[$id] = $resolver;
    }

    public function get(string $id)
    {
        if (!isset($this->bindings[$id])) {
            throw new \RuntimeException("Service {$id} not found in container");
        }
        $resolver = $this->bindings[$id];
        return $resolver();
    }
}
