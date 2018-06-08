<?php

namespace Morrislaptop\Firestore\Tests;

class DocumentTest extends TestCase
{
    /**
     * @var CollectionReference
     */
    private $collection;

    public function setUp()
    {
        $this->collection = self::$firestore->collection(self::$testCollection);
    }

    /**
     * @param $key
     * @param $value
     *
     * @dataDisabledProvider validValues
     */
    public function testSetAndGet()
    {
        $doc = $this->collection->document(__FUNCTION__);

        $doc->set($this->validValues());

        $snap = $doc->snapshot();

        foreach ($this->validValues() as $key => $value) {
            $this->assertSame($value, $snap[$key]);
        }
    }

    public function testSetMergeAndGet()
    {
        $doc = $this->collection->document(__FUNCTION__);

        $doc->set([
            'first' => 'value',
            'second' => 'value',
        ]);

        $doc->set([
            'first' => 'updated',
            'third' => 'new',
        ], true);

        $expected = [
            'first' => 'updated',
            'second' => 'value',
            'third' => 'new',
        ];

        $this->assertEquals($expected, $doc->snapshot()->data());
    }

    public function validValues()
    {
        return [
            'string' => 'value',
            'int' => 1,
            'bool_true' => true,
            'bool_false' => false,
            // 'array' => ['first' => 'value', 'second' => 'value'],
        ];
    }
}
