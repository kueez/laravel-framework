<?php

namespace Illuminate\Console;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\Exception\CommandNotFoundException;

class ContainerCommandLoader implements CommandLoaderInterface
{
    /**
     * The container instance.
     *
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * A map of command names to classes.
     * 
     * @var array
     */
    protected $commandMap;

    /**
     * @param  \Psr\Container\ContainerInterface $container
     * @param  array  $commandMap
     */
    public function __construct(ContainerInterface $container, array $commandMap)
    {
        $this->container = $container;
        $this->commandMap = $commandMap;
    }

    /**
     * Loads a command.
     *
     * @param  string  $name
     * @return \Symfony\Component\Console\Command\Command
     *
     * @throws \Symfony\Component\Console\Exception\CommandNotFoundException
     */
    public function get(string $name)
    {
        if (!$this->has($name)) {
            throw new CommandNotFoundException(sprintf('Command "%s" does not exist.', $name));
        }

        return $this->container->get($this->commandMap[$name]);
    }

    /**
     * Checks if a command exists.
     *
     * @param  string  $name
     * @return bool
     */
    public function has(string $name)
    {
        return $name && isset($this->commandMap[$name]);
    }

    /**
     * Get the command names.
     * 
     * @return string[]
     */
    public function getNames()
    {
        return array_keys($this->commandMap);
    }
}