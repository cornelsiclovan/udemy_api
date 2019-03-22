<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.03.2019
 * Time: 13:35
 */

namespace App\Exception;
use Symfony\Component\Config\Definition\Exception\Exception;
use Throwable;

class InvalidConfirmationTokenException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct('Confirmation token is invalid.', $code, $previous);
    }
}