<?php

namespace SimpleSAML\Module\sildisco\Auth\Process;

use SAML2\XML\saml\NameID;
use SimpleSAML\Auth\ProcessingFilter;
use SimpleSAML\Error;
use SimpleSAML\Error\Exception;
use SimpleSAML\Logger;
use SimpleSAML\Metadata\MetaDataStorageHandler;

/**
 * Attribute filter for appending IDPNamespace to the NameID.
 * The IdP must have a IDPNamespace entry in its metadata.
 *
 * Also, for this to work, the SP needs to include a line in its
 * authsources.php file in the IdP's entry ...
 *   'NameIDPolicy' => [
 *     'Format' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
 *     'AllowCreate' => true,
 *   ],
 *
 */
class AddIdp2NameId extends ProcessingFilter
{

    const IDP_KEY = "saml:sp:IdP"; // the key that points to the entity id in the state

    // the metadata key for the IDP's Namespace code (i.e. short name)
    const IDP_CODE_KEY = 'IDPNamespace';

    const DELIMITER = '@'; // The symbol between the NameID proper and the Idp code.

    const SP_NAMEID_ATTR = 'saml:sp:NameID'; // The key for the NameID

    const VALUE_KEY = 'Value';  // The value key for the NamedID entry

    const ERROR_PREFIX = "AddIdp2NameId: "; // Text to go at the beginning of error messages

    const FORMAT_KEY = 'Format';

    /**
     * What NameQualifier should be used.
     * Can be one of:
     *  - a string: The qualifier to use.
     *  - FALSE: Do not include a NameQualifier. This is the default.
     *  - TRUE: Use the IdP entity ID.
     *
     * @var string|bool
     */
    private string|bool $nameQualifier;


    /**
     * What SPNameQualifier should be used.
     * Can be one of:
     *  - a string: The qualifier to use.
     *  - FALSE: Do not include a SPNameQualifier.
     *  - TRUE: Use the SP entity ID. This is the default.
     *
     * @var string|bool
     */
    private string|bool $spNameQualifier;


    /**
     * The format of this NameID.
     *
     * This property must be initialized the subclass.
     */
    protected ?string $format;


    /**
     * Initialize this filter, parse configuration.
     *
     * @param array $config Configuration information about this filter.
     * @param mixed $reserved For future use.
     */
    public function __construct(array $config, mixed $reserved)
    {
        parent::__construct($config, $reserved);
        assert('is_array($config)');

        if (isset($config['NameQualifier'])) {
            $this->nameQualifier = $config['NameQualifier'];
        } else {
            $this->nameQualifier = false;
        }

        if (isset($config['SPNameQualifier'])) {
            $this->spNameQualifier = $config['SPNameQualifier'];
        } else {
            $this->spNameQualifier = true;
        }

        $this->format = Null;
        if (!empty($config[self::FORMAT_KEY])) {
            $this->format = (string)$config[self::FORMAT_KEY];
        }
    }

    /**
     * @param $nameId NameID
     * @param $IDPNamespace string
     *
     * Modifies the nameID object by adding text to the end of its value attribute
     */
    public function appendIdp(NameID $nameId, string $IDPNamespace): void
    {

        $suffix = self::DELIMITER . $IDPNamespace;
        $value = $nameId->getValue();
        $nameId->setValue($value . $suffix);
    }


    /**
     * @inheritDoc
     * @throws Exception
     */
    public function process(array &$state): void
    {
        assert('is_array($state)');

        $samlIDP = $state[self::IDP_KEY];

        if (empty($state[self::SP_NAMEID_ATTR])) {
            Logger::warning(
                self::SP_NAMEID_ATTR . ' attribute not available from ' .
                $samlIDP . '.'
            );
            return;
        }

        $metadata = MetaDataStorageHandler::getMetadataHandler();
        $idpEntry = $metadata->getMetaData($samlIDP, 'saml20-idp-remote');

        // The IDP metadata must have an IDPNamespace entry
        if (!isset($idpEntry[self::IDP_CODE_KEY])) {
            throw new Error\Exception(self::ERROR_PREFIX . "Missing required metadata entry: " .
                self::IDP_CODE_KEY . ".");
        }

        // IDPNamespace must be a non-empty string
        if (!is_string($idpEntry[self::IDP_CODE_KEY])) {
            throw new Error\Exception(self::ERROR_PREFIX . "Required metadata " .
                "entry, " . self::IDP_CODE_KEY . ", must be a non-empty string.");
        }

        // IDPNamespace must not have special characters in it
        if (!preg_match("/^[A-Za-z0-9_-]+$/", $idpEntry[self::IDP_CODE_KEY])) {
            throw new Error\Exception(self::ERROR_PREFIX . "Required metadata " .
                "entry, " . self::IDP_CODE_KEY . ", must not be empty or contain anything except " .
                "letters, numbers, hyphens and underscores.");
        }

        $IDPNamespace = $idpEntry[self::IDP_CODE_KEY];

        $nameId = $state[self::SP_NAMEID_ATTR];
        self::appendIdp($nameId, $IDPNamespace);

        $format = 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent';

        if (!empty($this->format)) {
            $format = $this->format;
        } elseif (!empty($nameId->Format)) {
            $format = $nameId->Format;
        }

        $state['saml:NameID'][$format] = $nameId;

    }

}
