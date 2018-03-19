<?php

namespace App\Mappers;

use App\Entity\ShortUrl;

class ShortUrlMapper
{
    /**
     * Conver array to Entity
     *
     * @param type $data
     *
     * @return ShortUrl
     */
    public static function toEntity($data): ShortUrl
    {
        return (new ShortUrl())
            ->setId((int) $data['id'])
            ->setUrl($data['url'])
            ->setShortUrl($data['short_url']);
    }
}
