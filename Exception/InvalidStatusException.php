<?php

namespace SumoCoders\FrameworkMultiUserBundle\Exception;

use Exception;
use SumoCoders\FrameworkMultiUserBundle\ValueObject\Status;

final class InvalidStatusException extends Exception
{
    /**
     * @param string $status
     *
     * @return InvalidStatusException
     */
    public static function withStatus($status)
    {
        return new self(
            sprintf(
                'Invalid status %s, possible statuses are: %s.',
                $status,
                implode(', ', Status::getPossibleStatuses())
            )
        );
    }
}
