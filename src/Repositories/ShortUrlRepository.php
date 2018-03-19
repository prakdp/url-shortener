<?php

namespace App\Repositories;

use App\Entity\ShortUrl;
use App\Mappers\ShortUrlMapper;
use App\Repository;

/**
 * ShortUrl Repository
 */
class ShortUrlRepository extends Repository
{
    /**
     * Generate short URL by URL
     *
     * @param string $url
     *
     * @return string Short URL
     */
    protected function generateShortUrl(string $url): string
    {
        return substr(md5($url . uniqid('shorturl')), 0, 10);
    }

    /**
     * Create ShortUrl Entity
     *
     * @param string $url
     *
     * @return ShortUrl
     */
    public function createByUrl(string $url): ShortUrl
    {
        $shortUrl = $this->generateShortUrl($url);
        $entity = (new ShortUrl())
            ->setUrl($url)
            ->setShortUrl($shortUrl);

        $stmt = $this->getDB()->prepare('INSERT INTO short_url (url, short_url) VALUES (?, ?)');
        $stmt->execute([$entity->getUrl(), $entity->getShortUrl()]);

        $id = $this->getDB()->lastInsertId();

        $entity->setId($id);

        return $entity;
    }

    /**
     * Find ShortUrl Entity by short URL
     *
     * @param string $shortUrl
     *
     * @return ShortUrl|null
     */
    public function findByShortUrl(string $shortUrl): ?ShortUrl
    {
        $stmt = $this->getDB()->prepare("SELECT * FROM short_url WHERE short_url=?");
        $stmt->execute([$shortUrl]);
        $data = $stmt->fetch();

        return empty($data) ? null : ShortUrlMapper::toEntity($data);
    }
}
