<?php

class HeaderSorter {
  private $debugging;
  public function __construct($debugging) {
    $this->debugging = $debugging;
  }

  function sort($filePath) {
    if (!endsWith($filePath, ".h") && !endsWith("$filePath", ".m")) {
      return false;
    }

    $workingFilePath = $filePath;
    if ($this->debugging) {
      $workingFilePath = $filePath . "changed";
      if (copy($filePath, $workingFilePath) === false) {
        echo "Fail to copy the file";
        return;
      }
    }
    
    echo $workingFilePath . "\n";
    $header = array();
    $body = array();
    
    $openfile = fopen($workingFilePath, "r+");
    $headerOffset = -1;
    $lineIndex = 0;

    while ($openfile && ($line = fgets($openfile)) !== false) {
      
      if (!startsWith($line, "#import")) {
        // remove #import header
        array_push($body, $line);
      } else {
        if ($headerOffset < 0) {
          $headerOffset = $lineIndex;
        }
        array_push($header, $line);
      }
      $lineIndex ++;
    }
    fclose($openfile);
    
    asort($header);
    array_splice($body, $headerOffset, 0, $header);
    
    $reduceWhitespaceBody =
        $this->removeEmptyLine($body, $headerOffset, count($header));
    return $this->writeOutputToFile($workingFilePath, $reduceWhitespaceBody);
  }  
  
  function removeEmptyLine($body, $headerStartingOffset, $headerLenght) {
    $headerEndedOffset = $headerStartingOffset + $headerLenght;

    $recordLength = 0;
    $index = $headerStartingOffset;
    while ($headerStartingOffset >= 0) {
      $index --;
      if (hasEmptyLine($body[$index])) {
        $recordLength++;
      } else {
        break;
      }
    }
    $recordLength -= 1; // min 1 extra space
    if ($headerStartingOffset - $recordLength >= 0 && $recordLength > 0) {
      array_splice($body, $headerStartingOffset - $recordLength, $recordLength);
      $headerEndedOffset -= $recordLength;
    }

    $recordLength = 0;
    $index = $headerEndedOffset;
    while ($lineIndex < count($body)) {
      if (hasEmptyLine($body[$index])) {
        $recordLength++;
      } else {
        break;
      }
      $index ++;
    }
    $recordLength -= 1;
    if ($headerEndedOffset + $recordLength < count($body) && $recordLength > 0) {
      array_splice($body, $headerEndedOffset, $recordLength);
    }
    
    return $body;
  }
  
  
  function writeOutputToFile($filepath, $outputArr) {
    $outString = implode("", $outputArr);
    if (file_put_contents($filepath, $outputArr)) {
      echo "Success\n";
      return true;
    } else {
      echo "failed\n";
      return false;
    }
  }
}

function hasEmptyLine($line) {
  return $line === "\n" || $line === 'n';
}

function endsWith($haystack, $needle) {
  return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
}       

function startsWith($haystack, $needle) {
  return $needle === "" || strpos($haystack, $needle) === 0;
}

?>