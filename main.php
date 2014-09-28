<?php

include 'sortHeader.php';

$inputFileDir = $argv[1];

echo "input file dir:" . $inputFileDir . "\n";

$listFile = scandir($inputFileDir, SCANDIR_SORT_ASCENDING);
$sorter = new HeaderSorter(false);
foreach ($listFile as $filename) {
  $filepath = $inputFileDir . "/" . $filename;
  $sorter->sort($filepath);
}
?>