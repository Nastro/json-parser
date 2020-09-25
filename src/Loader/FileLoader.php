<?php

namespace JsonParser\Loader;

use JsonParser\Exceptions\InvalidPathFileException;

class FileLoader implements ILoader
{
    /** @var string */
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * {@inheritDoc}
     * @throws InvalidPathFileException
     */
    public function load(): ?string
    {
        if (!is_file($this->path)) {
            throw new InvalidPathFileException(sprintf('Файл %s не существует', $this->path));
        }

        return file_get_contents($this->path);
    }
}
