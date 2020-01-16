<?php

namespace KoninklijkeCollective\KoningGeo\Exception;

/**
 * Exception: No Location Record Found
 */
class NoLocationFoundException extends \Exception implements ExtensionException
{
    /**
     * @param  int  $identifier
     * @param  string  $table
     * @return \KoninklijkeCollective\KoningGeo\Exception\NoLocationFoundException
     */
    public static function invalidRecord(int $identifier, string $table): NoLocationFoundException
    {
        return new self(
            'No record found with ID (' . $identifier . ') within table (' . $table . ')',
            1579101636
        );
    }
}
