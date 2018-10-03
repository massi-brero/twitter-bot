<?php
/**
 * Created by PhpStorm.
 * User: massi
 * Date: 03.10.18
 * Time: 14:39
 */

namespace TwitterBot\Models;


final class EmailModel
{


    public static function send(string $to,
                         string $subject,
                         string $message) : bool
    {
        return mail($to, $subject, $message);
    }


}