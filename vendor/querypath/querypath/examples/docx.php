<?php
/**
 * DocX Parser
 *
 * For namespaces use | instead of :
 *
 * 
 * @author Emily Brand
 * @license LGPL The GNU Lesser GPL (LGPL) or an MIT-like license.
 * @see http://www.urbandictionary.com/
 */
require_once '../src/QueryPath/QueryPath.php';
$path = 'http://eabrand.com/images/test.docx';

//$path = 'docx_document.xml';

$data = docx2text('test.docx');

$path = $data;


foreach(qp($path, 'w|p') as $qp) {
  $qr = $qp->branch();
  print format($qr->find('w|r:first'), 'w|r:first').' ';
  $qp->find('w|r:first');
  while($qp->next('w|r')->html() != null) {
    $qr = $qp->branch();
    print format($qr->find('w|r'), 'w|r').' ';
    //    print $qp->text();
  }
  print '</br>';
}

/**
 *
 * @param QueryPath $qp
 * @param String $findSelector
 * @return String
 */
function format($qp, $findSelector = null) {

  // Create a new branch for printing later.
  $qr = $qp->branch();

  $text = "";

  $text = $qr->find($findSelector)->find('w|t')->text();

  $text = (checkUnderline($qp->branch())) ? '<u>'.$text.'</u>' : $text;
  $text = (checkBold($qp->branch())) ? '<b>'.$text.'</b>' : $text;

  return $text;
}

/**
 *
 * @param QueryPath $qp
 * @return String
 */
function checkBold($qp) {
  $qp->children("w|rPr");
  return ($qp->children('w|b')->html()) ? true : false;
}

/**
 *
 * @param QueryPath $qp
 * @return String
 */
function checkUnderline($qp) {
  $qp->children("w|rPr");
  return ($qp->children('w|u')->html()) ? true : false;
}


function docx2text($filename) {
    return readZippedXML($filename, "word/document.xml");
}

function readZippedXML($archiveFile, $dataFile) {
  if (!class_exists('ZipArchive', false)) {
      return "ZipArchive Class Doesn't Exist.";
  }
    // Create new ZIP archive
    $zip = new ZipArchive();
    // Open received archive file
    if (true === $zip->open($archiveFile)) {
        // If done, search for the data file in the archive
        if (($index = $zip->locateName($dataFile)) !== false) {
            // If found, read it to the string
            $data = $zip->getFromIndex($index);
            // Close archive file
            $zip->close();
            // Load XML from a string
            // Skip errors and warnings
            return $data;
//            $xml = DOMDocument::loadXML($data, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
//            // Return data without XML formatting tags
//            return strip_tags($xml->saveXML());
        }
        $zip->close();
    }

    // In case of failure return empty string
    return $zip->getStatusString();
} 
