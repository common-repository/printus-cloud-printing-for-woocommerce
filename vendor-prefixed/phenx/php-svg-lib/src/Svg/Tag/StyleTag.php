<?php
/**
 * @package php-svg-lib
 * @link    http://github.com/PhenX/php-svg-lib
 * @author  Fabien Ménager <fabien.menager@gmail.com>
 * @license GNU LGPLv3+ http://www.gnu.org/copyleft/lesser.html
 *
 * Modified by __root__ on 28-October-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace Printus\Svg\Tag;

use Printus\Sabberworm\CSS;

class StyleTag extends AbstractTag
{
    protected $text = "";

    public function end()
    {
        $parser = new CSS\Parser($this->text);
        $this->document->appendStyleSheet($parser->parse());
    }

    public function appendText($text)
    {
        $this->text .= $text;
    }
} 
