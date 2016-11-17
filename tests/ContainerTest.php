<?php

namespace Felipecwb\Tests;

use Felipecwb\Container;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    /**
     * @test
     */
    public function setAndGet()
    {
        $c = new Container();
        $c->set('message', 'Hello World!');
        $c->set('config', ['environment' => 'test']);

        $this->assertInstanceOf('Felipecwb\\Container', $c);
        $this->assertEquals('Hello World!', $c->get('message'));
        $this->assertEquals(['environment' => 'test'], $c->get('config'));

        $c->set('closure', function ($container) {
            $this->assertInstanceOf('Felipecwb\\Container', $container);
            return 'Returned Data';
        });

        $result = $c->get('closure');
        $this->assertEquals('Returned Data', $result);

        $c->set('cube', function ($container) {
            return function ($n) {
                return $n * $n * $n;
            };
        });

        $cube = $c->get('cube');
        $this->assertSame($cube, $c->get('cube'));
    }

    /**
     * @test
     * @expectedException \LogicException
     * @expectedExceptionMessageRegExp /Identifier: \w+ not exists!/
     */
    public function getUnknowIdentifier()
    {
        $c = new Container();
        $c->get('unknow');
    }

    /**
     * @test
     */
    public function magicCall()
    {
        $c = new Container();
        $c->set('message', 'Hello World!');
        $c->set('config', ['environment' => 'test']);

        $this->assertInstanceOf('Felipecwb\\Container', $c);
        $this->assertEquals('Hello World!', $c->getMessage());
        $this->assertEquals(['environment' => 'test'], $c->getConfig());

        $c->set('cube', function ($container) {
            return function ($n) {
                return $n * $n * $n;
            };
        });

        $cube = $c->getCube();
        $this->assertSame($cube, $c->getCube());
    }

    /**
     * @test
     * @dataProvider provideMethodName
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessageRegExp /Method '\w+' does not exists!/
     */
    public function magicCallWithUnknowMethod($method)
    {
        $c = new Container();
        $c->{$method}();
    }

    public function provideMethodName()
    {
        return [
            ['unknow'],
            ['a1'],
            ['unexistingMethod']
        ];
    }
}
