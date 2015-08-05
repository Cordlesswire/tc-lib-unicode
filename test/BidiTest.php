<?php
/**
 * BidiTest.php
 *
 * @since       2011-05-23
 * @category    Library
 * @package     Unicode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2011-2015 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-unicode
 *
 * This file is part of tc-lib-unicode software library.
 */

namespace Test;

/**
 * Bidi Test
 *
 * @since       2011-05-23
 * @category    Library
 * @package     Unicode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2011-2015 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-unicode
 */
class BidiTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        //$this->markTestSkipped(); // skip this test
    }
    
    public function testException()
    {
        $this->setExpectedException('\Com\Tecnick\Unicode\Exception');
        new \Com\Tecnick\Unicode\Bidi(null, null, null, false);
    }

    /**
     * @dataProvider inputDataProvider
     */
    public function testStr($str, $charr, $ordarr, $forcertl)
    {
        $bidi = new \Com\Tecnick\Unicode\Bidi($str, $charr, $ordarr, $forcertl);
        $this->assertEquals('test', $bidi->getString());
        $this->assertEquals(array('t', 'e', 's', 't'), $bidi->getChrArray());
        $this->assertEquals(array(116, 101, 115, 116), $bidi->getOrdArray());
        $this->assertEquals(array(116 => true, 101 => true, 115 => true), $bidi->getCharKeys());
    }

    public function inputDataProvider()
    {
        return array(
            array('test', null, null, false),
            array(null, array('t', 'e', 's', 't'), null, false),
            array(null, null, array(116, 101, 115, 116), false),
            array('test', array('t', 'e', 's', 't'), null, false),
            array('test', null, array(116, 101, 115, 116), false),
            array(null, array('t', 'e', 's', 't'), array(116, 101, 115, 116), false),
            array('test', array('t', 'e', 's', 't'), array(116, 101, 115, 116), false),
            array('test', null, null, 'L'),
            array('test', null, null, 'R'),
        );
    }

    /**
     * @dataProvider bidiStrDataProvider
     */
    public function testBidiStr($str, $expected, $forcertl = false)
    {
        $bidi = new \Com\Tecnick\Unicode\Bidi($str, null, null, $forcertl);
        $this->assertEquals($expected, $bidi->getString());
    }

    public function bidiStrDataProvider()
    {
        return array(
            array(
                json_decode('"The words \"\u202b\u05de\u05d6\u05dc [mazel] '
                    .'\u05d8\u05d5\u05d1 [tov]\u202c\" mean \"Congratulations!\""'),
                'The words "[tov] בוט [mazel] לזמ" mean "Congratulations!"'
            ),
            array(
                'اختبار بسيط',
                'ﻂﻴﺴﺑ ﺭﺎﺒﺘﺧﺍ'
            ),
            array(
                json_decode('"\u0671AB\u0679\u0683"'),
                'ﭷﭩABٱ'
            ),
            array(
                json_decode('"\u067137\u0679\u0683"'),
                'ﭷﭩ37ٱ'
            ),
            array(
                json_decode('"AB\u0683"'),
                'ABﭶ'
            ),
            array(
                json_decode('"AB\u0683"'),
                'ABﭶ',
                'L'
            ),
            array(
                json_decode('"AB\u0683"'),
                'ﭶAB',
                'R'
            ),
            array(
                json_decode('"he said \"\u0671\u0679! \u0683\" to her"'),
                'he said "ﭧٱ! ﭶ" to her'
            ),
            array(
                json_decode('"he said \"\u0671\u0679!\" to her"'),
                'he said "ﭧٱ!" to her'
            ),
            array(
                json_decode('"he said \"\u0671\u0679! \u200F\" to her"'),
                'he said "ﭧٱ! ‏" to her'
            ),
            array(
                json_decode('"START CODES \u202bRLE\u202a LRE \u202eRLO\u202d LRO \u202cPDF\u202c END"'),
                'START CODES RLE LRE FDP LRO OLR END'
            ),
            array(
                json_decode('"\u202EABC\u202C"'),
                'CBA'
            ),
            array(
                json_decode('"\u202D\u0671\u0679\u0683\u202C"'),
                'ٱﭩﭷ'
            ),
            array(
                json_decode('"START RLE'
                    .'\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b'
                    .'\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b'
                    .'\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b'
                    .'\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b'
                    .'\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b'
                    .'\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b'
                    .'\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b'
                    .'\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b'
                    .'\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b'
                    .'\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b'
                    .'\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b'
                    .'\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b'
                    .'\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b'
                    .'\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b'
                    .'\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b'
                    .'\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b'
                    .'\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b'
                    .'\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b'
                    .'\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b\u202b'
                    .'"'),
                'START RLE'
            ),
        );
    }

    /**
     * @dataProvider bidiOrdDataProvider
     */
    public function testBidiOrd($ordarr, $expected, $forcertl = false)
    {
        $bidi = new \Com\Tecnick\Unicode\Bidi(null, null, $ordarr, $forcertl);
        $this->assertEquals($expected, $bidi->getOrdArray());
        
    }

    public function bidiOrdDataProvider()
    {
        return array(
            array(
                array(1649,65,66,1657,1667),
                array(64375,64361,65,66,1649),
            ),
            array(
                array(1667,1657,65,66,1649),
                array(64337,65,66,64361,64376),
            ),
            array(
                array(65,66,1667),
                array(65,66,64374),
            ),
            array(
                array(65,66,1667),
                array(65,66,64374),
                'L'
            ),
            array(
                array(65,66,1667),
                array(64374,65,66),
                'R'
            ),
            array(
                array(917760,917761,917762,917763),
                array(917763,917762,917761,917760),
                'R'
            ),
            array(
                array(48,8314,48,8314,48,8314),
                array(8314,48,8314,48,8314,48),
                'R'
            ),
            array(
                array(1632,8239,1632,8239,1632,8239),
                array(8239,1632,8239,1632,8239,1632),
                'R'
            ),
            array(
                array(1667,1657,65,66,48,162,49,50,1649),
                array(64337,65,66,48,162,49,50,64361,64376),
            ),
            array(
                array(65,66,1636,1637,1667,8233),
                array(65,66,64374,1636,1637,8233),
            ),/*
            array(
                array(8295,65,66,67,8297,65,1667,1657),
                array(65,66,67,65,64359,64376),
            ),
            array(
                array(8294,65,8297,66,8295,67,8297,68,8296,69,8297),
                array(65,66,67,68,69),
                'R'
            ),
            array(
                array(65,8297,8297,66,8236,8236,67),
                array(65,66,67),
                'R'
            ),
            array(
                array(65,66,13,67,68,10,69,70,31,71,72),
                array(65,66,13,67,68,10,71,72,31,69,70),
                'R'
            ),
            array(
                array(59,60,8235,61,62,8234,63,64,8236,91,92,8236,8235,93,94,8236,95,96),
                array(59,60,94,91,92,93,63,64,60,61,95,96),
                'L'
            ),
            array(
                array(59,60,8295,61,62,8294,63,64,8297,91,92,8297,8295,93,94,8297,95,96),
                array(59,60,94,91,92,93,63,64,60,61,95,96),
                'L'
            ),
            array(
                array(59,60,8235,61,62,8294,63,64,8235,91,92,8297,93,94,8236,95,96),
                array(59,60,94,91,63,64,92,93,60,61,95,96),
                'L'
            ),*/
        );
    }
}
