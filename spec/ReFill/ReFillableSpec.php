<?php

namespace spec\ReFill;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReFillableSpec extends ObjectBehavior
{
    function let()
    {
        $uniqueId = 0;
        $string = "John Smith";
        $this->beConstructedWith($uniqueId, $string);
    }
    function it_is_initializable()
    {
        $this->shouldHaveType('ReFill\ReFillable');
    }

    function it_should_remove_unindexable_characters()
    {
        $this->clean("/user/test/I am a long url's_-?ASDF@£$%£%^testé.html")
            ->shouldReturn("usertesti am a long urls-asdftesthtml");

        $this->clean("/Images/../4-icon.jpg     ")
            ->shouldReturn("images4-iconjpg");

    }

    public function it_should_split_words()
    {
        $this->splitWords(
            "Always code as if the guy who ends up " .
            "maintaining your code will be a violent psychopath " .
            "who knows where you live."
        )->shouldReturn([
            'Always', 'code', 'as', 'if', 'the', 'guy', 'who', 'ends',
            'up', 'maintaining', 'your', 'code', 'will', 'be', 'a',
            'violent', 'psychopath', 'who', 'knows', 'where', 'you',
            'live.'
        ]);
    }

    public function it_should_build_an_index_array_for_a_word()
    {
        $this->buildIndex('programming')
            ->shouldReturn(
                [
                    'p',
                    'pr',
                    'pro',
                    'prog',
                    'progr',
                    'progra',
                    'program',
                    'programm',
                    'programmi',
                    'programmin',
                    'programming',
                ]
            );
    }

    public function it_should_discard_stop_words()
    {
        $this->discardInvalidWords(
            [
                'Always', 'code', 'as', 'if', 'the', 'guy',
                'who', 'ends', 'up', 'maintaining', 'your',
                'code', 'will', 'be', 'a', 'violent',
                'psychopath', 'who', 'knows', 'where',
                'you', 'live.'
            ]
        )->shouldReturn(
            [
                0 => 'Always', 1 => 'code', 5 => 'guy',
                7 => 'ends', 9 => 'maintaining', 10 => 'your',
                11 => 'code', 15 => 'violent',
                16 => 'psychopath', 18 => 'knows',
                20 => 'you', 21 => 'live.'
            ]
        );
    }
}
