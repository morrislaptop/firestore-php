<?php

namespace Morrislaptop\Firestore\Tests;

class CollectionTest extends TestCase
{
    /**
     * @var CollectionReference
     */
    private $collection;

    public function setUp()
    {
        $this->collection = self::$firestore->collection('test-lists');
    }

    public function testDocuments()
    {
        // Arrange.
        for ($i = 0; $i < 30; $i++) {
            $doc = $this->collection->document(__FUNCTION__ . $i);
            $doc->set(['test' => true]);
        }

        // Act.
        $docs = $this->collection->documents(['pageSize' => 100]);

        // Assert.
        $this->assertSame($docs->size(), 30);
    }

    public function testWhere()
    {
        // Arrange.
        for ($i = 0; $i < 5; $i++) {
            $doc = $this->collection->document(__FUNCTION__ . $i);
            // $doc->set(['number' => $i]);
        }

        // Act.
        $docs = $this->collection->where('number', '>', 3)->documents();

        // Assert.
        $this->assertSame($docs->size(), 2);
    }
}
