<?php

namespace Libeo\Bundle\ApiBundle\Normalizer;
use Pim\Bundle\TransformBundle\Normalizer\Structured\ProductNormalizer;
use Doctrine\Common\Collections\ArrayCollection;

class LibeoApiProductNormalizer extends ProductNormalizer
{
    protected function normalizeValues(ArrayCollection $values, $format, array $context = [])
    {
        foreach ($this->valuesFilters as $filter) {
            $values = $filter->filter($values, $context);
        }

        $data = [];
        foreach ($values as $value) {
            $attribute = $value->getAttribute();
            $group = $attribute->getGroup()->getCode();
            $data[$group][$value->getAttribute()->getCode()][] = $this->serializer->normalize($value, $format, $context);
        }

        return $data;
    }
}