<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19.03.2019
 * Time: 14:36
 */

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Controller\UploadImageAction;

/**
 * @ORM\Entity()
 * @Vich\Uploadable()
 * @ApiResource(
 *      attributes={
 *          "order"={"id": "ASC"},
 *          "formats"={"json", "jsonld", "form"={"multipart/form-data"}},
 *     },
 *      collectionOperations={
            "get",
 *          "post"={
                "method"="POST",
 *              "path"="/images",
 *              "controller"=UploadImageAction::class,
 *              "defaults"={"_api_receive"=false}
 *          }
 *     },
 *     itemOperations={
 *          "get",
 *          "delete"={
                "access_control"="is_granted('ROLE_WRITER')"
 *          }
 *     }
 * )
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Vich\UploadableField(mapping="images", fileNameProperty="url")
     * @Assert\NotNull()
     */
    private $file;

    /**
     * @ORM\Column(nullable=true)
     * @Groups({"get-blog-post-with-author"})
     */
    private $url;

    public function getId()
    {
        return $this->id;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;
    }

    public function getUrl()
    {
        return '/images/'.$this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function __toString(): string
    {
        return $this->id. ':' .$this->url;
    }
}