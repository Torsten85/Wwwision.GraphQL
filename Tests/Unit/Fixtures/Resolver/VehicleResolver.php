<?php
namespace Wwwision\GraphQL\Tests\Unit\Fixtures\Resolver;

use Wwwision\GraphQL\Tests\Unit\Fixtures\Type\Airplane;
use GraphQL\Type\Definition\ResolveInfo;
use Wwwision\GraphQL\GraphQLContext;
use Wwwision\GraphQL\Resolver;

class VehicleResolver extends Resolver
{
    /**
     * @param $value
     * @param GraphQLContext $context
     * @param ResolveInfo $info
     * @return string
     */
    public function resolveType($value, GraphQLContext $context, ResolveInfo $info): string
    {
        if ($value instanceof Airplane) {
            return 'Airplane';
        }
        return 'Car';
    }
}
