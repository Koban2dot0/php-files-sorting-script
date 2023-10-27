<?php

declare(strict_types=1);

$pathToBaseFolder = '/home/koban/Downloads';

/**
 * @throws Exception
 */
function startSorting($pathToBaseFolder): void
{
    if (!is_dir($pathToBaseFolder)) {
        $colorRedOpen = '\033[31m';
        $colorRedClose = '\033[0m';
        throw new Exception($colorRedOpen . "Given path: $pathToBaseFolder is not a folder!" . $colorRedClose . "\n");
    }

    $files = getFilesFromFolder($pathToBaseFolder);
    $assignedFiles = assignFileFormatToItsNames($files, $pathToBaseFolder);

    $formats = getFilesFormats($assignedFiles);
    createFoldersAccordingToFormats($formats, $pathToBaseFolder);

    putFilesToCreatedFolders($assignedFiles, $pathToBaseFolder);

    deleteCopiedFiles($assignedFiles, $pathToBaseFolder);
}

/**
 * @param string $pathToBaseFolder
 * @return string[]
 */
function getFilesFromFolder(string $pathToBaseFolder): array
{
    $folderContent = scandir($pathToBaseFolder);
    return removeReferenceToCurrentAndParentFolder($folderContent);
}

/**
 * @param string[] $files
 * @return string[]
 */
function assignFileFormatToItsNames(array $files, string $pathToBaseFolder): array
{
    $result = [];
    foreach ($files as $file) {
        if (is_dir($pathToBaseFolder . DIRECTORY_SEPARATOR . $file)) {
            continue;
        }
        $format = pathinfo($pathToBaseFolder . DIRECTORY_SEPARATOR . $file, PATHINFO_EXTENSION);
        if ($format === '') {
            $format = 'Files with no format';
        }
        $result[$format][] = $file;
    }
    return $result;
}

function createFoldersAccordingToFormats(array $formats, string $pathToBaseFolder): void
{
    foreach ($formats as $format) {
        $pathToFormatFolder = $pathToBaseFolder . DIRECTORY_SEPARATOR . $format;
        if (!file_exists($pathToFormatFolder)) {
            mkdir($pathToFormatFolder);
        }
    }
}

function putFilesToCreatedFolders(array $assignedFiles, string $pathToBaseFolder): void
{
    foreach ($assignedFiles as $folderName => $files) {
        foreach ($files as $fileName) {
            $from = $pathToBaseFolder . DIRECTORY_SEPARATOR . $fileName;
            $to = $pathToBaseFolder . DIRECTORY_SEPARATOR . $folderName . DIRECTORY_SEPARATOR . $fileName;
            copy($from, $to);
        }
    }
}

function deleteCopiedFiles(array $assignedFiles, string $pathToBaseFolder): void
{
    foreach ($assignedFiles as $files) {
        foreach ($files as $fileName) {
            $filePath = $pathToBaseFolder . DIRECTORY_SEPARATOR . $fileName;
            if (is_file($filePath)) {
                unlink($filePath);
            }
        }
    }
}

/**
 * @param string[] $folderContent
 * @return string[]
 */
function removeReferenceToCurrentAndParentFolder(array $folderContent): array
{
    return array_diff($folderContent, ['.', '..']);
}

function getFilesFormats(array $assignedFiles): array
{
    return array_keys($assignedFiles);
}

startSorting($pathToBaseFolder);
