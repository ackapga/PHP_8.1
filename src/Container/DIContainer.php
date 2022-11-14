<?php

namespace Ackapga\Habrahabr\Container;

use Ackapga\Habrahabr\Exceptions\NotFoundException;
use Psr\Container\ContainerInterface;
use ReflectionClass;


class DIContainer implements ContainerInterface
{
    /**
     * @param string $id
     * @return bool
     */
    public function has(string $id): bool
    {

        try {
            $this->get($id);
        } catch (NotFoundException $e) {
            return false;
        }
        return true;
    }

    /**
     * @var array
     */
    private array $resolvers = [];

    /**
     * @param string $type
     * @param $resolver
     * @return void
     */
    public function bind(string $type, $resolver)
    {
        $this->resolvers[$type] = $resolver;
    }

    /**
     * @param string $id
     * @return object
     * @throws NotFoundException
     */
    public function get(string $id): object
    {
        if (array_key_exists($id, $this->resolvers)) {
            $typeToCreate = $this->resolvers[$id];

            if (is_object($typeToCreate)) {
                return $typeToCreate;
            }
            return $this->get($typeToCreate);
        }

        if (!class_exists($id)) {
            throw new NotFoundException("Не удается разрешить тип: $id");
        }

        $reflectionClass = new ReflectionClass($id);

        $constructor = $reflectionClass->getConstructor();

        if (null === $constructor) {
            return new $id();
        }

        $parameters = [];

        foreach ($constructor->getParameters() as $parameter) {

            $parameterType = $parameter->getType()->getName();

            $parameters[] = $this->get($parameterType);
        }

        return new $id(...$parameters);

    }

}

