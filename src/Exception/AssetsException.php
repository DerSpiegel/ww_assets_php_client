<?php

namespace DerSpiegel\WoodWingAssetsClient\Exception;

use RuntimeException;
use Throwable;


class AssetsException extends RuntimeException
{
    public static function createFromCode(string $message = "", int $code = 0, ?Throwable $previous = null): AssetsException
    {
        return match ($code) {
            BadRequestAssetsException::CODE => new BadRequestAssetsException($message, $code, $previous),
            NotAuthorizedAssetsException::CODE => new NotAuthorizedAssetsException($message, $code, $previous),
            default => new AssetsException($message, $code, $previous)
        };
    }
}
