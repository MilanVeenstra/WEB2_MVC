<?php

namespace Framework\DependencyInjection;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface {
    public static function createFromConfig(string $root_dir, string $filename): self
    {
        // TODO: Implement createFromConfig() method.
        // 1. Create a new container.
        // 2. Get the contents of the configuration file.
        // 3. Register services in configuration file.
        // 4. Register root directory as a string.
    }

    public function registerService(string $id, ?string $class = null, bool $singleton = true, mixed ...$parameters): void
    {
        // TODO: Implement registerService() method.
        $this->config[$id] = ['class' => $class, 'parameters' => $parameters, 'singleton' => $singleton];
    }

    public function registerString(string $id, string $string): void
    {
        // TODO: Implement registerString() method.
        $this->strings[$id] = $string;
    }

    private function getConfig(string $id): array
    {
        // TODO: Implement getConfig() method.
        // 1. Get the config for the service id.
        // 2. Replace missing parameters with defaults.
    }

    private function getClassNameForService(string $id): string
    {
        // TODO: Implement getClassNameForService() method.
        // 1. Check if the class is set explicitly in config.
        // 2. Check if the service id is a class name.
        // 3. Check if the service id is an interface name that ends in `Interface`
        // 4. If so, try the corresponding class name without `Interface`
    }

    private function replaceServices(mixed $value): mixed
    {
        // TODO: Implement replaceServices() method.
        // 1. If the value is an array, call method recursively on all values.
        // 2. If the value is a string and starts with an @, replace it with the corresponding service.
        // 3. Otherwise, replace string placeholders in value
    }

    private function getConstructorParams(\ReflectionClass $class, array $params): array
    {
        // TODO: Implement getConstructorParams() method.
        // 1. Get class constructor
        // 2. Iterate over constructor parameters
        // 3. If the parameter is present in config, replace services in parameter value
        // 4. If the parameter is not present and not optional, get a service using autowiring
        // 5. If the parameter is not present and optional, use the default value
        // 6. Return array containing constructor parameter values
    }

    private function create(array $config): object
    {
        // TODO: Implement create() method.
        // 1. Create reflection class object based on class of service
        // 2. Get array of constructor parameters based on configured parameters
        // 3. Create new instance of class with constructor parameters
    }

    private function cacheSingleton(string $id, callable $factory): object
    {
        // TODO: Implement cacheSingleton() method.
        // 1. Call factory if service is not cached and cache result
        // 2. Return cached service
    }

    #[\Override] public function get(string $id) {
        // TODO: Implement get() method.
        // 1. Throw NotFoundException if id does not exit in container
        // 2. Get configuration for id
        // 3. Create factory callback for service based on id
        // 4. Cache service if it is a singleton
        // 5. Otherwise call factory
        // 5. Throw Exception if an error occurs while creating service
    }

    #[\Override] public function has(string $id): bool
    {
        // TODO: Implement has() method.
    }
}