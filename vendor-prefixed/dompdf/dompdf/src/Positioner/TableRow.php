<?php
/**
 * @package dompdf
 * @link    https://github.com/dompdf/dompdf
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 *
 * Modified by __root__ on 28-October-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */
namespace Printus\Dompdf\Positioner;

use Printus\Dompdf\FrameDecorator\AbstractFrameDecorator;

/**
 * Positions table rows
 *
 * @package dompdf
 */
class TableRow extends AbstractPositioner
{

    /**
     * @param AbstractFrameDecorator $frame
     */
    function position(AbstractFrameDecorator $frame): void
    {
        $cb = $frame->get_containing_block();
        $p = $frame->get_prev_sibling();

        if ($p) {
            $y = $p->get_position("y") + $p->get_margin_height();
        } else {
            $y = $cb["y"];
        }
        $frame->set_position($cb["x"], $y);
    }
}
