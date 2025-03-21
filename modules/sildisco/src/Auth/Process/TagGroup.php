<?php

namespace SimpleSAML\Module\sildisco\Auth\Process;

use SimpleSAML\Auth\ProcessingFilter;
use SimpleSAML\Error\MetadataNotFound;
use SimpleSAML\Metadata\MetaDataStorageHandler;

/**
 * Attribute filter for prefixing group names
 *
 */
class TagGroup extends ProcessingFilter
{

    const IDP_NAME_KEY = 'name'; // the metadata key for the IDP's name

    // the metadata key for the IDP's Namespace code (i.e. short name to be prefixed to groups)
    const IDP_CODE_KEY = 'IDPNamespace';


    public function prependIdp2Groups(array $attributes, string $attributeLabel, string $idpLabel): array
    {
        $newGroups = [];
        $delimiter = '|';

        foreach ($attributes[$attributeLabel] as $group) {
            $newGroups[] = "idp" . $delimiter . $idpLabel . $delimiter . $group;
        }
        return $newGroups;
    }


    /**
     * Apply filter to copy attributes.
     *
     * @inheritDoc
     * @throws MetadataNotFound
     */
    public function process(array &$state): void
    {
        assert('is_array($request)');
        assert('array_key_exists("Attributes", $request)');

        $attributes =& $state['Attributes'];

        // urn:oid:2.5.4.31  is for 'member' (like groups)
        $oid4member = 'urn:oid:2.5.4.31';
        $member = 'member';

        if (empty($attributes[$oid4member]) && empty($attributes[$member])) {
            return;
        }

        $metadata = MetaDataStorageHandler::getMetadataHandler();

        $samlIDP = $state["saml:sp:IdP"];

        $idpEntry = $metadata->getMetaData($samlIDP, 'saml20-idp-remote');

        /*
         *  If the IDP metadata has an IDPNamespace entry, use that value.  Otherwise,
         * if there is a name entry, use that value.  Otherwise,
         * use the IDP's entity id.
         */
        if (isset($idpEntry[self::IDP_CODE_KEY]) &&
            is_string($idpEntry[self::IDP_CODE_KEY])) {
            $idpLabel = $idpEntry[self::IDP_CODE_KEY];
        } else if (isset($idpEntry[self::IDP_NAME_KEY]) &&
            is_string($idpEntry[self::IDP_NAME_KEY])) {
            $idpLabel = $idpEntry[self::IDP_NAME_KEY];
        } else {
            $idpLabel = $samlIDP;
        }

        $idpLabel = str_replace(' ', '_', $idpLabel);

        foreach ([$oid4member, $member] as $nextAttribute) {
            if (!empty($attributes[$nextAttribute])) {
                $attributes[$nextAttribute] = self::prependIdp2Groups(
                    $attributes,
                    $nextAttribute,
                    $idpLabel);
            }
        }
    }

}
