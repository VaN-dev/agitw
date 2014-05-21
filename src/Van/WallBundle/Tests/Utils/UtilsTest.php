<?php
// src/Van/WallBundle/Tests/Utils/UtilsTest.php
namespace Van\WallBundle\Tests\Utils;

use Van\WallBundle\Utils\Utils;

class UtilsTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildLink() {
        $this->assertEquals('<a href="http://www.google.com">www.google.com</a>', Utils::buildLink('www.google.com'));
        $this->assertEquals('<a href="http://www.google.com?q=test">www.google.com?q=test</a>', Utils::buildLink('www.google.com?q=test'));
        $this->assertEquals('<a href="http://www.google.com">http://www.google.com</a>', Utils::buildLink('http://www.google.com'));
        $this->assertEquals('<a href="http://google.com">http://google.com</a>', Utils::buildLink('http://google.com'));
        $this->assertEquals('<a href="http://www.google.fr/#q=test">http://www.google.fr/#q=test</a>', Utils::buildLink('http://www.google.fr/#q=test'));
        $this->assertEquals('Si tu cliques sur le lien <a href="http://www.google.com">http://www.google.com</a> tu iras sur la homepage de Google.', Utils::buildLink('Si tu cliques sur le lien http://www.google.com tu iras sur la homepage de Google.'));
    }
}