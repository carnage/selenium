<?php

namespace Carnage\Selenium\ValueObject;


class Url
{
    /**
     * @var string
     */
    private $scheme;
    
    /**
     * @var string
     */
    private $host;
    
    /**
     * @var string
     */
    private $port;
    
    /**
     * @var Uri
     */
    private $uri;

    /**
     * Url constructor.
     * @param $scheme
     * @param $host
     * @param $port
     * @param $uri
     */
    public function __construct($scheme, $host, $port, Uri $uri)
    {
        $this->scheme = $scheme;
        $this->host = $host;
        $this->port = $port;
        $this->uri = $uri;
    }

    public static function fromString($url)
    {
        $bits = parse_url($url);
        $bits['scheme'] = isset($bits['scheme']) ? $bits['scheme'] : 'http';
        $bits['host'] = isset($bits['host']) ? $bits['host'] : '';
        $bits['port'] = isset($bits['port']) ? $bits['port'] : '80';
        $bits['path'] = isset($bits['path']) ? $bits['path'] : '/';
        $bits['query'] = isset($bits['query']) ? $bits['query'] : '';


        return new static($bits['scheme'], $bits['host'] , $bits['port'], new Uri($bits['path'], $bits['query']));
    }

    public function __toString()
    {
        return $this->scheme . '://' . $this->host . ':' . $this->port . (string) $this->uri;
    }

    public function withUri(Uri $uri)
    {
        return new static($this->scheme, $this->host, $this->port, $uri);
    }

    public function equals(self $other)
    {
        return $this->scheme === $other->scheme &&
            $this->host === $other->host &&
            $this->port === $other->port &&
            $this->uri->equals($other->uri);
    }
}