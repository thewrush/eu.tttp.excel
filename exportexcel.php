<?php

function exportexcel_civicrm_export( $exportTempTable, $headerRows, $sqlColumns, $exportMode ) {

  $writeHeader = true;
  $offset = 0;
  $limit  = 200;

  $query = "
    SELECT *
    FROM   $exportTempTable
    ";
  require_once 'CRM/Core/Report/Excel.php';
  while ( 1 ) {
    $limitQuery = $query . "
      LIMIT $offset, $limit
      ";
    $dao = CRM_Core_DAO::executeQuery( $limitQuery );

    if ( $dao->N <= 0 ) {
      break;
    }

    $componentDetails = array( );
    while ( $dao->fetch( ) ) {
      $row = array( );

      foreach ( $sqlColumns as $column => $dontCare ) {
        $row[$column] = $dao->$column;
      }

      $componentDetails[] = $row;
    }
    CRM_Core_Report_Excel::writeHTMLFile( "Export_Records", $headerRows,
        $componentDetails, null, $writeHeader );
    $writeHeader = false;
    $offset += $limit;
  }

  CRM_Utils_System::civiExit( );
}
