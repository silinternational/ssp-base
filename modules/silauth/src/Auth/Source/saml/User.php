<?php

namespace SimpleSAML\Module\silauth\Auth\Source\saml;

class User
{
    public static function convertToSamlFieldNames(
        string  $employeeId,
        string  $firstName,
        string  $lastName,
        string  $username,
        string  $email,
        string  $uuid,
        string  $idpDomainName,
        ?string $passwordExpirationDate,
        array   $mfa,
        array   $method,
        ?string $managerEmail,
        string  $profileReview,
        array   $member
    ): array {

        // eduPersonUniqueId (only alphanumeric allowed)
        $alphaNumericUuid = str_replace('-', '', $uuid);
        $eduPersonUniqueId = $alphaNumericUuid . '@' . $idpDomainName;

        return [
            'eduPersonPrincipalName' => [
                $username . '@' . $idpDomainName,
            ],

            /**
             * Don't use this misspelled version of eduPersonTargetedID. (Accidentally used in the past)
             * @deprecated
             *
             * NOTE: Do NOT include eduPersonTargetedID. If you need it, use the
             * core:TargetedID module (at the Hub, if using one) to generate an
             * eduPersonTargetedID based on the eduPersonUniqueId attribute (below).
             *
             */
            'eduPersonTargetID' => (array)$uuid, // Incorrect, deprecated

            /**
             * Use this for a globally unique, non-human friendly, non-reassignable attribute
             **/
            'eduPersonUniqueId' => (array)$eduPersonUniqueId,

            'sn' => (array)$lastName,
            'givenName' => (array)$firstName,
            'displayName' => (array)trim($firstName + " " + $lastName),
            'mail' => (array)$email,
            'employeeNumber' => (array)$employeeId,
            'cn' => (array)$username,
            'schacExpiryDate' => (array)$passwordExpirationDate,
            'mfa' => $mfa,
            'method' => $method,
            'uuid' => (array)$uuid,
            'manager_email' => [$managerEmail ?? ''],
            'profile_review' => [$profileReview],
            'member' => $member,
        ];
    }
}
