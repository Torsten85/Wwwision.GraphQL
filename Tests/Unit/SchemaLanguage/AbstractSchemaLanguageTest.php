<?php
namespace Wwwision\GraphQL\Tests\Unit\SchemaLanguage;

use GraphQL\Executor\ExecutionResult;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use Neos\Cache\Backend\TransientMemoryBackend;
use Neos\Cache\Frontend\VariableFrontend;
use Neos\Flow\Http\Request as HttpRequest;
use Neos\Flow\ObjectManagement\ObjectManager;
use Neos\Flow\Tests\FunctionalTestCase;
use Wwwision\GraphQL\GraphQLContext;
use Wwwision\GraphQL\SchemaService;
use Neos\Flow\ObjectManagement\Configuration\Configuration as ObjectConfiguration;

abstract class AbstractSchemaLanguageTest extends FunctionalTestCase
{

    /**
     * @var SchemaService
     */
    protected $schemaService;

    /**
     * @var VariableFrontend
     */
    protected $schemaCache;

    /**
     * @var array
     */
    protected $resolvers = [];

    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @var string
     */
    protected $schema;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->schemaService = new SchemaService();

        $this->schemaCache = new VariableFrontend('Wwwision_GraphQL_Schema', new TransientMemoryBackend());
        $this->schemaCache->initializeObject();

        /** @var ObjectManager $objectManager */
        $objectManager = clone $this->objectManager;
        $objectManager->setObjects(array_reduce($this->resolvers, function (array $resolvers, string $resolverClassName) {
            $resolvers[$resolverClassName] = ['s' => ObjectConfiguration::SCOPE_PROTOTYPE];
            return $resolvers;
        }, []));

        $this->inject($this->schemaService, 'schemaCache', $this->schemaCache);
        $this->inject($this->schemaService, 'objectManager', $objectManager);
        $this->inject($this->schemaService, 'endpointsConfiguration',[
            $this->endpoint => [
                'schema' => __DIR__ . '/../Fixtures/' . $this->schema . '.graphql',
                'resolverPathPattern' => 'Wwwision\GraphQL\Tests\Unit\Fixtures\Resolver\{Type}Resolver'

            ]
        ]);

        GraphQL::setDefaultFieldResolver([SchemaService::class, 'defaultFieldResolver']);
    }

    /**
     * @param Schema $schema
     * @param string $query
     * @return ExecutionResult
     */
    protected function executeQuery(Schema $schema, string $query): ExecutionResult
    {
        $request = HttpRequest::createFromEnvironment();
        $context = new GraphQLContext($request);
        return GraphQL::executeQuery($schema, $query, null, $context);
    }

}
