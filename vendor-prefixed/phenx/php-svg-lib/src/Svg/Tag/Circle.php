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

use Printus\Svg\Style;

class Circle extends Shape
{
    protected $cx = 0;
    protected $cy = 0;
    protected $r;

    public function start($attributes)
    {
        if (isset($attributes['cx'])) {
            $width = $this->document->getWidth();
            $this->cx = $this->convertSize($attributes['cx'], $width);
        }
        if (isset($attributes['cy'])) {
            $height = $this->document->getHeight();
            $this->cy = $this->convertSize($attributes['cy'], $height);
        }
        if (isset($attributes['r'])) {
            $diagonal = $this->document->getDiagonal();
            $this->r = $this->convertSize($attributes['r'], $diagonal);
        }

        $this->document->getSurface()->circle($this->cx, $this->cy, $this->r);
    }
} 
