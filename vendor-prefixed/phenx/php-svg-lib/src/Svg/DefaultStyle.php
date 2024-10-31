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

namespace Printus\Svg;

class DefaultStyle extends Style
{
    public $color = [0, 0, 0, 1];
    public $opacity = 1.0;
    public $display = 'inline';

    public $fill = [0, 0, 0, 1];
    public $fillOpacity = 1.0;
    public $fillRule = 'nonzero';

    public $stroke = 'none';
    public $strokeOpacity = 1.0;
    public $strokeLinecap = 'butt';
    public $strokeLinejoin = 'miter';
    public $strokeMiterlimit = 4;
    public $strokeWidth = 1.0;
    public $strokeDasharray = 0;
    public $strokeDashoffset = 0;
}
