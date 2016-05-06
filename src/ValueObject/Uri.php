<?php

namespace Carnage\Selenium\ValueObject;


class Uri
{
    private $path;
    private $query;

    /**
     * Uri constructor.
     * @param $path
     * @param $query
     */
    public function __construct($path, $query)
    {
        $this->path = $path;
        $this->query = $query;
    }

    public static function fromString($uri)
    {
        $bits = parse_url($uri);
        $bits['path'] = isset($bits['path']) ? $bits['path'] : '';
        $bits['query'] = isset($bits['query']) ? $bits['query'] : '';
        
        return new static($bits['path'], $bits['query']);
    }

    public function __toString()
    {
        return $this->path . '?' . $this->query;
    }

    public function equals(self $other)
    {
        return $this->path === $other->path && $this->query === $other->query;
    }
}