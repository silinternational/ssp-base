<?php
namespace Sil\SspExpiryChecker;

use Exception;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;

class Validator
{
    const INT = 'int';
    const NOT_EMPTY = 'not empty';
    const STRING = 'string';
    
    /**
     * Validate the given value against the specified rules.
     *
     * @param mixed $value The value to check.
     * @param string[] $rules The list of rules (see this class's constants).
     * @param LoggerInterface $logger The logger.
     * @throws Exception
     */
    public static function validate($value, $rules, $logger, $attribute)
    {
        foreach ($rules as $rule) {
            if ( ! self::isValid($value, $rule, $logger)) {
                
                $exception = new Exception(sprintf(
                    'The value we have for %s (%s) does not meet the following validation rule: %s.',
                    $attribute,
                    var_export($value, true),
                    $rule
                ), 1496867717);
                
                $logger->critical($exception->getMessage());
                throw $exception;
            }
        }
    }
    
    /**
     * See if the given value satisfies the specified rule.
     *
     * @param mixed $value The value to check.
     * @param string $rule The rule (see this class's constants).
     * @param LoggerInterface $logger The logger.
     * @return bool
     * @throws InvalidArgumentException
     */
    protected static function isValid($value, $rule, $logger)
    {
        switch ($rule) {
            case self::INT:
                return is_int($value);
                
            case self::NOT_EMPTY:
                return !empty($value);
                
            case self::STRING:
                return is_string($value);
                
            default:
                $exception = new InvalidArgumentException(sprintf(
                    'Unknown validation rule: %s',
                    var_export($rule, true)
                ), 1496866914);
                
                $logger->critical($exception->getMessage());
                throw $exception;
        }
    }
}
