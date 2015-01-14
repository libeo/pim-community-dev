<?php

namespace Libeo\Bundle\ApiBundle\Normalizer;
use Pim\Bundle\TransformBundle\Normalizer\Structured\CategoryNormalizer;

class LibeoApiCategoryNormalizer extends CategoryNormalizer
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = array())
    {
        $data = array(
                'code'    => $object->getCode(),
                'parent'  => $object->getParent() ? $object->getParent()->getCode() : '',
            ) + $this->transNormalizer->normalize($object, $format, $context);

        if (isset($context['resource'])) {
            $data['resource'] = $context['resource'];
        }

        return $data;
    }
}