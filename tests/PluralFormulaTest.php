<?php

/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Test for gettext parsing.
 */
class PluralFormlulaTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test for extractPluralsForms.
     *
     *
     * @dataProvider pluralExtractionData
     */
    public function testExtractPluralsForms($header, $expected)
    {
        $this->assertEquals(
            $expected,
            MoTranslator\Translator::extractPluralsForms($header)
        );
    }

    public function pluralExtractionData()
    {
        return array(
            // It defaults to a "Western-style" plural header.
            array(
                '',
                'nplurals=2; plural=n == 1 ? 0 : 1;',
            ),
            // Extracting it from the middle of the header works.
            array(
                "Content-type: text/html; charset=UTF-8\n"
                . "Plural-Forms: nplurals=1; plural=0;\n"
                . "Last-Translator: nobody\n",
                ' nplurals=1; plural=0;',
            ),
            // It's also case-insensitive.
            array(
                "PLURAL-forms: nplurals=1; plural=0;\n",
                ' nplurals=1; plural=0;',
            ),
            // It falls back to default if it's not on a separate line.
            array(
                'Content-type: text/html; charset=UTF-8' // note the missing \n here
                . "Plural-Forms: nplurals=1; plural=0;\n"
                . "Last-Translator: nobody\n",
                'nplurals=2; plural=n == 1 ? 0 : 1;',
            ),
        );
    }

    /**
     * @dataProvider pluralCounts
     */
    public function testPluralCounts($expr, $expected)
    {
        $this->assertEquals(
            $expected,
            MoTranslator\Translator::extractPluralCount($expr)
        );
    }

    public function pluralCounts()
    {
        return array(
            array('', 1),
            array('foo=2; expr', 1),
            array('nplurals=2; epxr', 2),
            array(' nplurals = 3 ; epxr', 3),
            array(' nplurals = 4 ; epxr ; ', 4),
        );
    }

    /**
     * @dataProvider pluralExpressions
     */
    public function testPluralExpression($expr, $expected)
    {
        $this->assertEquals(
            $expected,
            MoTranslator\Translator::sanitizePluralExpression($expr)
        );
    }

    public function pluralExpressions()
    {
        return array(
            array('', ''),
            array(
                'nplurals=2; plural=n == 1 ? 0 : 1;',
                'n == 1 ? 0 : 1',
            ),
            array(
                ' nplurals=1; plural=0;',
                '0',
            ),
            array(
                "nplurals=6; plural=n==0 ? 0 : n==1 ? 1 : n==2 ? 2 : n%100>=3 && n%100<=10 ? 3 : n%100>=11 ? 4 : 5;\n",
                'n==0 ? 0 : n==1 ? 1 : n==2 ? 2 : n%100>=3 && n%100<=10 ? 3 : n%100>=11 ? 4 : 5',
            ),
            array(
                ' nplurals=1; plural=baz(n);',
                'baz(n)',
            ),
            array(
                ' plural=n',
                'n',
            ),
        );
    }
}
