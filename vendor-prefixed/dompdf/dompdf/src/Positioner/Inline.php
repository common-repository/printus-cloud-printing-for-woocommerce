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
use Printus\Dompdf\FrameDecorator\Inline as InlineFrameDecorator;
use Printus\Dompdf\Exception;
use Printus\Dompdf\Helpers;

/**
 * Positions inline frames
 *
 * @package dompdf
 */
class Inline extends AbstractPositioner
{

    /**
     * @param AbstractFrameDecorator $frame
     * @throws Exception
     */
    function position(AbstractFrameDecorator $frame): void
    {
        // Find our nearest block level parent and access its lines property
        $block = $frame->find_block_parent();

        if (!$block) {
            throw new Exception("No block-level parent found.  Not good.");
        }

        $cb = $frame->get_containing_block();
        $line = $block->get_current_line_box();

        if (!$frame->is_text_node() && !($frame instanceof InlineFrameDecorator)) {
            // Atomic inline boxes and replaced inline elements
            // (inline-block, inline-table, img etc.)
            $width = $frame->get_margin_width();
            $available_width = $cb["w"] - $line->left - $line->w - $line->right;

            if (Helpers::lengthGreater($width, $available_width)) {
                $block->add_line();
                $line = $block->get_current_line_box();
            }
        }

        $frame->set_position($cb["x"] + $line->w, $line->y);
    }
}
