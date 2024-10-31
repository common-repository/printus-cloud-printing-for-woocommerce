<?php
/**
 * @package dompdf
 * @link    https://github.com/dompdf/dompdf
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 *
 * Modified by __root__ on 28-October-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */
namespace Printus\Dompdf\Exception;

use Printus\Dompdf\Exception;

/**
 * Image exception thrown by DOMPDF
 *
 * @package dompdf
 */
class ImageException extends Exception
{

    /**
     * Class constructor
     *
     * @param string $message Error message
     * @param int $code       Error code
     */
    function __construct($message = null, $code = 0)
    {
        parent::__construct($message, $code);
    }

}
