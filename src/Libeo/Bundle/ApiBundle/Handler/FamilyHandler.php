<?php

namespace Libeo\Bundle\ApiBundle\Handler;

use Pim\Bundle\CatalogBundle\Model\FamilyInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Family handler
 *
 * @author    JF Boily <jean-francois.boily@libeo.com>
 */
class FamilyHandler
{
    /** @var SerializerInterface */
    protected $serializer;

    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Serialize a single family
     *
     * @param FamilyInterface $family
     * @param string[]         $channels
     * @param string[]         $locales
     * @param string           $url
     *
     * @return array
     */
    public function get(FamilyInterface $family, $channels, $locales, $url)
    {
        $data = $this->serializer->serialize(
            $family,
            'json',
            [
                'locales'  => $locales,
                'channels' => $channels,
                'resource' => $url
            ]
        );

        return $data;
    }
}
