<?php

/*
 * This file is part of the PHP Bench package
 *
 * (c) Daniel Leech <daniel@dantleech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhpBench\Tests\Unit\Report\Dom;

use PhpBench\Report\Dom\PhpBenchXpath;

class PhpBenchXpathTest extends \PHPUnit_Framework_TestCase
{
    private $xpath;

    public function setUp()
    {
        $dom = new \DOMDocument(1.0);
        $dom->loadXml(<<<EOT
<?xml version="1.0" ?>
<data>
    <row value="1" />
    <row value="2" />
    <row value="4" />
    <row value="8" />
</data>
EOT
        );
        $this->xpath = new PhpBenchXpath($dom);
    }

    /**
     * It calculate the deviation.
     */
    public function testDeviation()
    {
        $result = $this->xpath->evaluate('number(php:bench(\'deviation\', 5, 10))');
        $this->assertEquals(100, $result);
    }

    /**
     * It calculate the average.
     */
    public function testAverage()
    {
        $result = $this->xpath->evaluate('number(php:bench(\'avg\', //row/@value))');
        $this->assertEquals(3.75, $result);
    }

    /**
     * It should throw an exception if a non-scalar value is passed to evaluate.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testNonScalarEvaluate()
    {
        $this->xpath->evaluate(new \stdClass());
    }
}
