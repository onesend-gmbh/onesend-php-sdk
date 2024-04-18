<?php

declare(strict_types=1);

namespace OnesendGmbh\OnesendPhpSdk\Resources;

class ResourceFactory
{
    public static function createFromApiResponse(array $apiResponse, BaseResource $resource): BaseResource
    {
        foreach ($apiResponse as $responseProperty => $value) {
            $setter = 'set'.ucfirst($responseProperty);
            if (method_exists($resource, $setter)) {
                $resource->{$setter}($value);
            }
        }

        return $resource;
    }
}
