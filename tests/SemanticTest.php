<?php

use ReFill\Semantic;

class SemanticTest extends PHPUnit_Framework_TestCase
{

    public function test_that_sanitize_removes_special_characters()
    {

        $semantic = new Semantic;

        $actual = $semantic->sanitize("/user/test/I am a long url's_-?ASDF@£$%£%^testé.html");
         $this->assertEquals("usertesti am a long urls-asdftesthtml", $actual);

        $actual = $semantic->sanitize("/Images/../4-icon.jpg     ");
        $this->assertEquals("images4-iconjpg", $actual);
    }

    public function test_it_splits_a_string_into_words()
    {

        $semantic = new Semantic;

        $actual = $semantic->splitWords(
            "Always code as if the guy who ends up " .
            "maintaining your code will be a violent psychopath " .
            "who knows where you live."
        );

        $this->assertEquals([
            'Always', 'code', 'as', 'if', 'the', 'guy', 'who', 'ends',
            'up', 'maintaining', 'your', 'code', 'will', 'be', 'a',
            'violent', 'psychopath', 'who', 'knows', 'where', 'you',
            'live.'
        ], $actual);
    }

    public function test_it_builds_an_index_array_for_a_word()
    {
        $semantic = new Semantic;

        $actual = $semantic->buildIndex('programming', 3);

        $this->assertEquals(
                 [
//                    'p',
//                    'pr',
                     3  => 'pro',
                     4  => 'prog',
                     5  => 'progr',
                     6  => 'progra',
                     7  => 'program',
                     8  => 'programm',
                     9  => 'programmi',
                     10 => 'programmin',
                     11 =>'programming',
                 ],
                 $actual
             );
    }

    public function test_it_discards_stop_words()
    {

        $semantic = new Semantic;

        $actual = $semantic->discardInvalidWords(
            [
                'Always', 'code', 'as', 'if', 'the', 'guy',
                'who', 'ends', 'up', 'maintaining', 'your',
                'code', 'will', 'be', 'a', 'violent',
                'psychopath', 'who', 'knows', 'where',
                'you', 'live.'
            ],
            3
        );

        $this->assertEquals(
            [
                0 => 'Always', 1 => 'code', 5 => 'guy',
                7 => 'ends', 9 => 'maintaining', 10 => 'your',
                11 => 'code', 15 => 'violent',
                16 => 'psychopath', 18 => 'knows',
                20 => 'you', 21 => 'live.'
            ],
            $actual
        );
    }
}
