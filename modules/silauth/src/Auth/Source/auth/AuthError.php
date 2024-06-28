<?php

namespace SimpleSAML\Module\silauth\Auth\Source\auth;

/**
 * An immutable value object class for authentication error information (and
 * related constants and/or static functions).
 */
class AuthError
{
    const CODE_GENERIC_TRY_LATER = 'generic_try_later';
    const CODE_USERNAME_REQUIRED = 'username_required';
    const CODE_PASSWORD_REQUIRED = 'password_required';
    const CODE_INVALID_LOGIN = 'invalid_login';
    const CODE_NEED_TO_SET_ACCT_PASSWORD = 'need_to_set_acct_password';
    const CODE_RATE_LIMIT_SECONDS = 'rate_limit_seconds';
    const CODE_RATE_LIMIT_1_MINUTE = 'rate_limit_1_minute';
    const CODE_RATE_LIMIT_MINUTES = 'rate_limit_minutes';

    private string $code;
    private array $messageParams = [];

    /**
     * Constructor.
     *
     * @param string $code One of the AuthError::CODE_* constants.
     * @param array $messageParams The error message parameters.
     */
    public function __construct(string $code, array $messageParams = [])
    {
        $this->code = $code;
        $this->messageParams = $messageParams;
    }

    public function __toString()
    {
        return var_export([
            'code' => $this->code,
            'messageParams' => $this->messageParams,
        ], true);
    }

    /**
     * Get the error code, which will be one of the AuthError::CODE_* constants.
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Get the error string that should be passed to simpleSAMLphp's translate
     * function for this AuthError. It will correspond to an entry in the
     * appropriate dictionary file provided by this module.
     *
     * @return string Example: '{silauth:error:generic_try_later}'
     */
    public function getFullSspErrorTag(): string
    {
        return sprintf(
            '{%s:%s}',
            'silauth:error',
            $this->getCode()
        );
    }

    public function getMessageParams(): array
    {
        return $this->messageParams;
    }
}
