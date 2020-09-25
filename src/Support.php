<?php

namespace JsonParser;

class Support
{
    /**
     * @param array $target
     * @param string $path
     * @param mixed $default
     * @return mixed
     */
    public static function getFromArray(array $target, $path, $default = null)
    {
        $paths = explode('.', $path);
        $currentFrom = $target;

        foreach ($paths as $pathChunk) {
            if (!isset($currentFrom[$pathChunk])) {
                return $default;
            }

            $currentFrom = $currentFrom[$pathChunk];
        }

        return $currentFrom;
    }
}
