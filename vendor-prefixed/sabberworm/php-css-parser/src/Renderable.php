<?php
/**
 * @license MIT
 *
 * Modified by __root__ on 28-October-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace Printus\Sabberworm\CSS;

interface Renderable
{
    /**
     * @return string
     */
    public function __toString();

    /**
     * @return string
     */
    public function render(OutputFormat $oOutputFormat);

    /**
     * @return int
     */
    public function getLineNo();
}
