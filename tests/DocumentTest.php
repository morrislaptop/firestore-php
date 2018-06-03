<?php

namespace Morrislaptop\Firestore\Tests;

class DocumentTest extends TestCase
{
    /**
     * @var Collection
     */
    private $collection;

    public function setUp()
    {
        $this->collection = self::$firestore->getCollection(self::$testCollection);
    }

    /**
     * @param $key
     * @param $value
     *
     * @dataDisabledProvider validValues
     */
    public function testSetAndGet()
    {
        $doc = $this->collection->getDocument(__FUNCTION__);

        $doc->set($this->validValues());

        $snap = $doc->getSnapshot();

        foreach ($this->validValues() as $key => $value) {
            $this->assertSame($value, $snap[$key]);
        }
    }

    public function testSetMergeAndGet()
    {
        $doc = $this->collection->getDocument(__FUNCTION__);
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

        $this->assertEquals($expected, $doc->getSnapshot()->data());
    }

    public function testPush()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');

        $ref = $this->ref->getChild(__FUNCTION__);
        $value = 'a value';

        $newRef = $ref->push($value);

        $this->assertSame(1, $ref->getSnapshot()->numChildren());
        $this->assertSame($value, $newRef->getValue());
    }

    public function testRemove()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');

        $ref = $this->ref->getChild(__FUNCTION__);

        $ref->set([
            'first' => 'value',
            'second' => 'value',
        ]);

        $ref->getChild('first')->remove();

        $this->assertEquals(['second' => 'value'], $ref->getValue());
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
