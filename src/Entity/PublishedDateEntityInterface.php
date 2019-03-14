<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.03.2019
 * Time: 12:50
 */

namespace App\Entity;
use DateTimeInterface;

interface PublishedDateEntityInterface
{
    public function setPublished(DateTimeInterface $published): PublishedDateEntityInterface;
}