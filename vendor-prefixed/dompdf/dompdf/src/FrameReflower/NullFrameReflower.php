<?php
/**
 * @package dompdf
 * @link    https://github.com/dompdf/dompdf
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 *
 * Modified by __root__ on 28-October-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */
namespace Printus\Dompdf\FrameReflower;

use Printus\Dompdf\Frame;
use Printus\Dompdf\FrameDecorator\Block as BlockFrameDecorator;

/**
 * Dummy reflower
 *
 * @package dompdf
 */
class NullFrameReflower extends AbstractFrameReflower
{

    /**
     * NullFrameReflower constructor.
     * @param Frame $frame
     */
    function __construct(Frame $frame)
    {
        parent::__construct($frame);
    }

    /**
     * @param BlockFrameDecorator|null $block
     */
    function reflow(BlockFrameDecorator $block = null)
    {
        return;
    }

}
