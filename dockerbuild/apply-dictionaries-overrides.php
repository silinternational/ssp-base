<?php

if (basename(getcwd()) !== 'overrides') {
    throw new Exception(sprintf(
        "Only run this in an 'overrides' folder, where the originals are in the parent folder.\n"
        . "Current directory: %s\n",
        getcwd()
    ));
}

function assertSuccess($result, $valueIndicatingFailure, $messageIfFailed)
{
    if ($result === $valueIndicatingFailure) {
        throw new Exception($messageIfFailed);
    }
}

foreach (glob('*.json') as $fileName) {
    echo sprintf(
        "Merging %s dictionary overrides with the original...\n",
        $fileName
    );

    $originalFile = realpath('../' . $fileName);
    assertSuccess($originalFile, false, 'Failed to find the original file.');
    $changesFile = realpath($fileName);
    assertSuccess($changesFile, false, 'Failed to find the file with changes.');

    $originalJson = file_get_contents($originalFile);
    assertSuccess($originalJson, false, 'Failed to read in the original file.');
    $changesJson = file_get_contents($changesFile);
    assertSuccess($changesJson, false, 'Failed to read in the file with changes.');

    $originalData = json_decode($originalJson, true);
    assertSuccess(
        $originalData,
        null,
        'Failed to decode the original JSON: ' . $originalJson
    );
    $changesData = json_decode($changesJson, true);
    assertSuccess($changesData, null, 'Failed to decode the changes JSON.');

    $combinedData = array_merge($originalData, $changesData);

    $combinedJson = json_encode($combinedData, JSON_PRETTY_PRINT);
    assertSuccess($combinedJson, false, 'Failed to encode the combined JSON.');

    $fileWriteResult = file_put_contents($originalFile, $combinedJson);
    assertSuccess(
        $fileWriteResult,
        false,
        'Failed to overwrite the original file with the combined JSON.'
    );

    echo sprintf("Merged '%s'.\n", $fileName);
}
