<?php
namespace Wwwision\GraphQL\Tests\Unit\Fixtures\Resolver;

use Wwwision\GraphQL\Tests\Unit\Fixtures\Type\Airplane;
use Wwwision\GraphQL\Resolver;

class QueryResolver extends Resolver
{
    /**
     * @return Airplane
     */
    public function myVehicle(): Airplane
    {
        return new Airplane(999);
    }

    /**
     * @return array
     */
    public function bestFriend(): array
    {
        return [
            'name' => 'BestFriend',
            'friends' => [
                [ 'name' => 'Friend #1' ],
                [ 'name' => 'Friend #2' ],
                [ 'name' => 'Friend #3' ]
            ]
        ];
    }

}
