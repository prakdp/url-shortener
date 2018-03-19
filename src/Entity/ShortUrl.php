<?php

namespace App\Entity;

class ShortUrl
{

    /**
     * ID
     *
     * @var int
     */
    protected $id;

    /**
     * Real URL
     * @var string
     */
    protected $url;

    /**
     * Short URL
     *
     * @var string
     */
    protected $shortUrl;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getShortUrl(): string
    {
        return $this->shortUrl;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function setShortUrl(string $shortUrl): self
    {
        $this->shortUrl = $shortUrl;
        return $this;
    }

}
