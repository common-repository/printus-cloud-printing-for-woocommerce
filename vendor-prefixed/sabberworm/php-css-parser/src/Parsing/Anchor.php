<?php
/**
 * @license MIT
 *
 * Modified by __root__ on 28-October-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace Printus\Sabberworm\CSS\Parsing;

class Anchor
{
    /**
     * @var int
     */
    private $iPosition;

    /**
     * @var \Printus\Sabberworm\CSS\Parsing\ParserState
     */
    private $oParserState;

    /**
     * @param int $iPosition
     * @param \Printus\Sabberworm\CSS\Parsing\ParserState $oParserState
     */
    public function __construct($iPosition, ParserState $oParserState)
    {
        $this->iPosition = $iPosition;
        $this->oParserState = $oParserState;
    }

    /**
     * @return void
     */
    public function backtrack()
    {
        $this->oParserState->setPosition($this->iPosition);
    }
}
