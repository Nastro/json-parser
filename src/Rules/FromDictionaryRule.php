<?php

namespace JsonParser\Rules;

use JsonParser\Support;
use JsonParser\Exceptions\UnknownDictionaryException;

class FromDictionaryRule extends AbstractRule{
    /** @var string */
    private $dictionaryName;

    /** @var string */
    private $path;

    /** @var callable */
    private $postProcessing;

    /**
     * @param string $path
     */
    public function __construct(string $dictionaryName, string $path, callable $postProcessing = null)
    {
        $this->dictionaryName = $dictionaryName;
        $this->path = $path;
        $this->postProcessing = $postProcessing;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(array $item)
    {
        $dictionary = $this->config->getDictionary($this->dictionaryName);

        if (!$dictionary) {
            throw new UnknownDictionaryException(sprintf('Справочник %s не существует', $this->dictionaryName));
        }

        $index = Support::getFromArray($item, $this->path);
        $value = $dictionary->getList()[$index] ?? null;

        if ($this->postProcessing) {
            return call_user_func_array($this->postProcessing, [$value]);
        }

        return $value;
    }
}
