<?php
namespace Modules\Product\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;

use MicroweberPackages\Core\tests\TestCase;
use Modules\Product\Support\CartesianProduct;

class CartesianProductGeneratorTest extends TestCase
{


    #[Test]


    public function it_empty_set(): void {
        $set = [];
        $result = new CartesianProduct($set);
        $this->assertCount(0, $result);
        $this->assertEquals([], $result->asArray());
    }

    #[Test]

    public function it_set_with_empty_array_subset(): void {
        $this->expectException(\InvalidArgumentException::class);
        $set = [
            'fruits' => ['strawberry', 'raspberry', 'blueberry'],
            'vegetables' => [],
            'drinks' => ['beer', 'whiskey']
        ];
        $result = new CartesianProduct($set);
        iterator_to_array($result);
    }

    #[Test]

    public function it_set_with_iterator_subset(): void {
        $set = [
            'fruit' => ['strawberry', 'raspberry'],
            'vegetable' => new \ArrayIterator(['potato', function () { return 'carrot'; }])
        ];

        $expected = [
            ['fruit' => 'strawberry', 'vegetable' => 'potato'],
            ['fruit' => 'strawberry', 'vegetable' => 'carrot'],
            ['fruit' => 'raspberry', 'vegetable' => 'potato'],
            ['fruit' => 'raspberry', 'vegetable' => 'carrot']
        ];

        $result = new CartesianProduct($set);
        $this->assertEquals($expected, $result->asArray());
    }

    #[Test]

    public function it_set_with_invalid_subset(): void {
        $this->expectException(\InvalidArgumentException::class);
        $set = [
            'fruits' => ['strawberry', 'raspberry', 'blueberry'],
            'vegetables' => new \stdClass(),
            'drinks' => ['beer', 'whiskey']
        ];
        $result = new CartesianProduct($set);
        iterator_to_array($result);
    }

    #[Test]

    public function it_count(): void {
        $set = [
            ['a', 'b', 'c', 'd', 'e', 'f'],
            [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
        ];
        $result = new CartesianProduct($set);
        $this->assertCount(60, $result);
    }

    #[Test]

    public function it_retrieve_current_combination(): void {
        $current = null;
        $set = [
            'hair' => ['blond', 'dark'],
            'skin' => ['white', 'black'],
            'eyes' => ['blue', function (array $combination) use (&$current) {
                if (null === $current) {
                    $current = $combination;
                }
                return 'green';
            }],
            'gender' => ['male', 'female']
        ];

        $result = new CartesianProduct($set);
        iterator_to_array($result);

        $this->assertNotNull($current);
        $this->assertIsArray($current);
        $this->assertArrayHasKey('hair', $current);
        $this->assertArrayHasKey('skin', $current);
        $this->assertArrayNotHasKey('eyes', $current);
        $this->assertArrayNotHasKey('gender', $current);
    }

    public function dataProvider(): array
    {
        return [
            'shapesAndColors' => $this->shapesAndColors(),
            'moreShapesThanColors' => $this->moreShapesThanColors(),
            'moreColorsThanShapes' => $this->moreColorsThanShapes(),
            'iCanHazClozures' => $this->iCanHazClozures()
        ];
    }

    private function shapesAndColors(): array
    {
        return [
            'cases' => [
                'color' => ['blue', 'red', 'green'],
                'shape' => ['round', 'square', 'triangle']
            ],
            'expected' => [
                ['color' => 'blue', 'shape' => 'round'],
                ['color' => 'blue', 'shape' => 'square'],
                ['color' => 'blue', 'shape' => 'triangle'],
                ['color' => 'red', 'shape' => 'round'],
                ['color' => 'red', 'shape' => 'square'],
                ['color' => 'red', 'shape' => 'triangle'],
                ['color' => 'green', 'shape' => 'round'],
                ['color' => 'green', 'shape' => 'square'],
                ['color' => 'green', 'shape' => 'triangle']
            ]
        ];
    }

    private function moreShapesThanColors(): array
    {
        return [
            'cases' => [
                'color' => ['blue', 'red'],
                'shape' => ['round', 'square', 'triangle']
            ],
            'expected' => [
                ['color' => 'blue', 'shape' => 'round'],
                ['color' => 'blue', 'shape' => 'square'],
                ['color' => 'blue', 'shape' => 'triangle'],
                ['color' => 'red', 'shape' => 'round'],
                ['color' => 'red', 'shape' => 'square'],
                ['color' => 'red', 'shape' => 'triangle']
            ]
        ];
    }

    private function moreColorsThanShapes(): array
    {
        return [
            'cases' => [
                'color' => ['blue', 'red', 'green'],
                'shape' => ['round', 'square']
            ],
            'expected' => [
                ['color' => 'blue', 'shape' => 'round'],
                ['color' => 'blue', 'shape' => 'square'],
                ['color' => 'red', 'shape' => 'round'],
                ['color' => 'red', 'shape' => 'square'],
                ['color' => 'green', 'shape' => 'round'],
                ['color' => 'green', 'shape' => 'square']
            ]
        ];
    }

    private function iCanHazClozures(): array
    {
        return [
            'cases' => [
                'color' => ['blue', function () { return 'red'; }],
                'shape' => ['round', function () { return 'square'; }]
            ],
            'expected' => [
                ['color' => 'blue', 'shape' => 'round'],
                ['color' => 'blue', 'shape' => 'square'],
                ['color' => 'red', 'shape' => 'round'],
                ['color' => 'red', 'shape' => 'square']
            ]
        ];
    }
}
