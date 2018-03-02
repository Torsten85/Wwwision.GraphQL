<?php
namespace Wwwision\GraphQL\Tests\Unit\Fixtures\Type;

class Airplane
{
    /**
    * @var int
    */
    protected $maxSpeed;

    /**
    * @param int $maxSpeed
    */
    public function __construct(int $maxSpeed)
    {
        $this->maxSpeed = $maxSpeed;
    }

    /**
     * @return int
     */
    public function getMaxSpeed(): int
    {
        return $this->maxSpeed;
    }

    /**
     * @return int
     */
    public function getWingspan(): int
    {
        return 120;
    }
}
