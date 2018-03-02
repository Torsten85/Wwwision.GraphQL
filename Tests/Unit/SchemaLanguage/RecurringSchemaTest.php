<?php
namespace Wwwision\GraphQL\Tests\Unit\SchemaLanguage;

use GraphQL\Type\Schema;
use Wwwision\GraphQL\Tests\Unit\Fixtures\Resolver\QueryResolver;

class RecurringSchemaTest extends AbstractSchemaLanguageTest
{

    /**
     * @var array
     */
    protected $resolvers = [
        QueryResolver::class
    ];

    /**
     * @var string
     */
    protected $endpoint = 'recurringEndpoint';

    /**
     * @var string
     */
    protected $schema = 'RecurringSchema';

    /**
     * @test
     */
    public function interfaceResolvesToCorrectType()
    {
        $schema = $this->schemaService->getSchemaForEndpoint($this->endpoint);
        $query = '
            query {
                bestFriend {
                    name
                    friends {
                        name
                        friends {
                            name
                        }
                    }
                }
            }
        ';

        $result = $this->executeQuery($schema, $query);
        $this->assertCount(0, $result->errors);

        $this->assertArrayHasKey('bestFriend', $result->data);
        $data = $result->data['bestFriend'];

        $this->assertEquals('BestFriend', $data['name']);
        $this->assertCount(3, $data['friends']);
        $this->assertEquals('Friend #1', $data['friends'][0]['name']);
        $this->assertNull($data['friends'][2]['friends']);
    }
}
