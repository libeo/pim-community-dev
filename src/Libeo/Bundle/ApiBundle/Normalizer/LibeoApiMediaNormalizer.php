<?php

namespace Libeo\Bundle\ApiBundle\Normalizer;
use Pim\Bundle\TransformBundle\Normalizer\Structured\MediaNormalizer;
use Symfony\Component\Routing\RouterInterface;

class LibeoApiMediaNormalizer extends MediaNormalizer
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = array())
    {
        $fname = $object->getFilename();

        $url = $this->router->generate(
            'pim_enrich_media_show',
            array(
                'filename' => $fname
            ),
            true
        );

        return $url;
    }
}