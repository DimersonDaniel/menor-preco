<?php


namespace App\Helpers;


class StatusResponse
{
    public static function errors($status_code, $headerOfMessage, $message)
    {
        return response(
            ['errors' =>
                [$headerOfMessage =>
                    [$message]
                ]
            ],$status_code);
    }
}