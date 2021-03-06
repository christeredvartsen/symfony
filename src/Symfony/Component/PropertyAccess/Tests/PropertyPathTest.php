<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\PropertyAccess\Tests;

use Symfony\Component\PropertyAccess\PropertyPath;
use Symfony\Component\PropertyAccess\Tests\Fixtures\Author;
use Symfony\Component\PropertyAccess\Tests\Fixtures\Magician;

class PropertyPathTest extends \PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $path = new PropertyPath('reference.traversable[index].property');

        $this->assertEquals('reference.traversable[index].property', $path->__toString());
    }

    /**
     * @expectedException \Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException
     */
    public function testInvalidPropertyPath_noDotBeforeProperty()
    {
        new PropertyPath('[index]property');
    }

    /**
     * @expectedException \Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException
     */
    public function testInvalidPropertyPath_dotAtTheBeginning()
    {
        new PropertyPath('.property');
    }

    /**
     * @expectedException \Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException
     */
    public function testInvalidPropertyPath_unexpectedCharacters()
    {
        new PropertyPath('property.$foo');
    }

    /**
     * @expectedException \Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException
     */
    public function testInvalidPropertyPath_empty()
    {
        new PropertyPath('');
    }

    /**
     * @expectedException \Symfony\Component\PropertyAccess\Exception\UnexpectedTypeException
     */
    public function testInvalidPropertyPath_null()
    {
        new PropertyPath(null);
    }

    /**
     * @expectedException \Symfony\Component\PropertyAccess\Exception\UnexpectedTypeException
     */
    public function testInvalidPropertyPath_false()
    {
        new PropertyPath(false);
    }

    public function testValidPropertyPath_zero()
    {
        new PropertyPath('0');
    }

    public function testGetParent_dot()
    {
        $propertyPath = new PropertyPath('grandpa.parent.child');

        $this->assertEquals(new PropertyPath('grandpa.parent'), $propertyPath->getParent());
    }

    public function testGetParent_index()
    {
        $propertyPath = new PropertyPath('grandpa.parent[child]');

        $this->assertEquals(new PropertyPath('grandpa.parent'), $propertyPath->getParent());
    }

    public function testGetParent_noParent()
    {
        $propertyPath = new PropertyPath('path');

        $this->assertNull($propertyPath->getParent());
    }

    public function testCopyConstructor()
    {
        $propertyPath = new PropertyPath('grandpa.parent[child]');
        $copy = new PropertyPath($propertyPath);

        $this->assertEquals($propertyPath, $copy);
    }

    public function testGetElement()
    {
        $propertyPath = new PropertyPath('grandpa.parent[child]');

        $this->assertEquals('child', $propertyPath->getElement(2));
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testGetElementDoesNotAcceptInvalidIndices()
    {
        $propertyPath = new PropertyPath('grandpa.parent[child]');

        $propertyPath->getElement(3);
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testGetElementDoesNotAcceptNegativeIndices()
    {
        $propertyPath = new PropertyPath('grandpa.parent[child]');

        $propertyPath->getElement(-1);
    }

    public function testIsProperty()
    {
        $propertyPath = new PropertyPath('grandpa.parent[child]');

        $this->assertTrue($propertyPath->isProperty(1));
        $this->assertFalse($propertyPath->isProperty(2));
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testIsPropertyDoesNotAcceptInvalidIndices()
    {
        $propertyPath = new PropertyPath('grandpa.parent[child]');

        $propertyPath->isProperty(3);
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testIsPropertyDoesNotAcceptNegativeIndices()
    {
        $propertyPath = new PropertyPath('grandpa.parent[child]');

        $propertyPath->isProperty(-1);
    }

    public function testIsIndex()
    {
        $propertyPath = new PropertyPath('grandpa.parent[child]');

        $this->assertFalse($propertyPath->isIndex(1));
        $this->assertTrue($propertyPath->isIndex(2));
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testIsIndexDoesNotAcceptInvalidIndices()
    {
        $propertyPath = new PropertyPath('grandpa.parent[child]');

        $propertyPath->isIndex(3);
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testIsIndexDoesNotAcceptNegativeIndices()
    {
        $propertyPath = new PropertyPath('grandpa.parent[child]');

        $propertyPath->isIndex(-1);
    }
}
