<?php

/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Test for gettext parsing.
 */
class PluralTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test for npgettext.
     *
     * @param int    $number   Number
     * @param string $expected Expected output
     *
     *
     * @dataProvider providerTestNpgettext
     */
    public function testNpgettext($number, $expected)
    {
        $parser = new MoTranslator\Translator(null);
        $result = $parser->npgettext(
            'context',
            "%d pig went to the market\n",
            "%d pigs went to the market\n",
            $number
        );
        $this->assertSame($expected, $result);
    }

    /**
     * Data provider for test_npgettext.
     *
     * @return array
     */
    public static function providerTestNpgettext()
    {
        return array(
            array(1, "%d pig went to the market\n"),
            array(2, "%d pigs went to the market\n"),
        );
    }
}
