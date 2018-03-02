<?php
namespace Wwwision\GraphQL\Tests\Unit\SchemaLanguage;

use GraphQL\Type\Schema;
use Wwwision\GraphQL\Tests\Unit\Fixtures\Resolver\QueryResolver;
use Wwwision\GraphQL\Tests\Unit\Fixtures\Resolver\VehicleResolver;

class SimpleSchemaTest extends AbstractSchemaLanguageTest
{

    /**
     * @var array
     */
    protected $resolvers = [
        VehicleResolver::class,
        QueryResolver::class
    ];

    /**
     * @var string
     */
    protected $endpoint = 'simpleEndpoint';

    /**
     * @var string
     */
    protected $schema = 'SimpleSchema';

    /**
     * @test
     */
    public function schemaIsBuildFromFileAndCached()
    {
        $schema = $this->schemaService->getSchemaForEndpoint($this->endpoint);
        $this->assertInstanceOf(Schema::class, $schema);
        $this->assertTrue($this->schemaCache->has($this->endpoint));
    }

    /**
     * @test
     */
    public function interfaceResolvesToCorrectType()
    {
        $schema = $this->schemaService->getSchemaForEndpoint($this->endpoint);
        $query = '
            query {
                myVehicle {
                    maxSpeed
                    ... on Airplane {
                        wingspan
                    }
                    ... on Car {
                        licensePlate
                    }
                }
            }
        ';

        $result = $this->executeQuery($schema, $query);
        $this->assertCount(0, $result->errors);
        $this->assertArrayHasKey('myVehicle', $result->data);
        $this->assertEquals(999, $result->data['myVehicle']['maxSpeed']);
        $this->assertArrayNotHasKey('licensePlate', $result->data);
        $this->assertEquals(120, $result->data['myVehicle']['wingspan']);
    }
}
