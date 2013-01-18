<?
header('Content-Type: application/octet-stream');
error_reporting(125);
$data = 'OK:';

function err( $error )
{
  die("err: $error");
}

function myerr( $err )
{
  $e = mysql_error();
  if( $e == '' ) $e = $err;
  if( $e == '' ) $e = 'unknown mysql error';
  mysql_free_result( mysql_query( 'ROLLBACK' ) );
  err( $e );
}

function pgerr( $err )
{
  $e = pg_last_error();
  if( $e == '' ) $e = $err;
  if( $e == '' ) $e = 'unknown pgsql error';
  pg_free_result( pg_query( 'ROLLBACK' ) );
  err( $e );
}


function dump( $val )
{
  global $data;
  $len = strlen($val);
  if( $len < 255 )
    $data .= chr($len);
  else
    $data .= "\xFF".pack('V',$len);
  $data .= $val;
  if( strlen($data) > 100000 ) {
    echo $data;
    $data = '';    
  }

}


if( !array_key_exists('server',$_POST) ) {
   header('Content-Type: text/html');
   echo "<html><head><title>EmsProxy v1.31</title></head><body><h1>EmsProxy v1.31</h1>emsproxy.php script is installed correctly.</body></html>";
   exit;
}


array_key_exists('server',$_POST) and array_key_exists('host',$_POST) and array_key_exists('port',$_POST) and array_key_exists('user',$_POST) and array_key_exists('password',$_POST) and array_key_exists('dbname',$_POST) or err("mailformed request");

if( get_magic_quotes_gpc() ) 
  foreach( $_POST as $key => $value ) 
    $_POST[$key] = stripslashes($value);


$commit = array_key_exists('commit',$_POST);

if( $_POST['server'] == 'mysql' ) {

  $host = $_POST['host'];
  if( $_POST['port'] )
    $host .= ':'.$_POST['port'];
  $conn = mysql_connect($host,$_POST['user'],$_POST['password']) or myerr('unknown connect error');
  if( $_POST['dbname'] != '' )
    mysql_select_db( $_POST['dbname'] ) or myerr('database not found');
  if( array_key_exists('charset',$_POST) && $_POST['charset'] != '' )
    mysql_query( '/*!40101 SET NAMES \'' . $_POST['charset'] . '\' */' ) or myerr('can not set character set');
  $result = FALSE;
  mysql_free_result( mysql_query( 'BEGIN' ) );
  for( $rn = 1; $rn < 1000; ++$rn ) {
    if( !array_key_exists( 'r'.$rn, $_POST ) )
      break;
    $data = 'OK:';
    $req = $_POST['r'.$rn];
    if( $req == 'connect' ) {
      dump( mysql_get_server_info() );
      dump( mysql_get_client_info() );
      dump( mysql_get_proto_info() );
      dump( mysql_get_host_info() );
    } else {
      $result = mysql_query($req) or myerr('unknown query execution error');
      if( $result === TRUE ) {
        dump( 0 );
        dump( mysql_affected_rows() );
      } else {
        $width = mysql_num_fields($result);
        $height = mysql_num_rows($result);
        dump($width);
        dump($height);
        for( $i = 0; $i < $width; ++$i ) {
          dump( mysql_field_name( $result, $i ) );
          $type = mysql_field_type( $result, $i );
          $len = mysql_field_len( $result, $i );
          $meta = mysql_fetch_field( $result, $i );
          $sflags = explode( ' ', mysql_field_flags ( $result, $i ) );
          $fl = 0;
          if( $meta->not_null ) $fl += 1;
          if( $meta->primary_key ) $fl += 2;
          if( $meta->unique_key ) $fl += 4;
          if( $meta->multiple_key ) $fl += 8;
          if( $meta->blob ) $fl += 16;
          if( $meta->unsigned ) $fl += 32;
          if( $meta->zerofill ) $fl += 64;
          if( in_array( 'binary', $sflags ) ) $fl += 128;
          if( in_array( 'enum', $sflags ) ) $fl += 256;
          if( in_array( 'auto_increment', $sflags ) ) $fl += 512;
          if( in_array( 'timestamp', $sflags ) ) $fl += 1024;
          if( in_array( 'set', $sflags ) ) $fl += 2048;
          if( $type == 'int' ) {
            if( $len > 11 ) $type = 8;                    # LONGLONG
            elseif( $len > 9 ) $type = 3;                 # LONG
            elseif( $len > 6 ) $type = 9;                 # INT24
            elseif( $len > 4 ) $type = 2;                 # SHORT
            else $type = 1;                               # TINY
          } elseif( $type == 'real' ) {
            if( $len == 12 ) $type = 4;                   # FLOAT     
            elseif( $len == 22 ) $type = 5;               # DOUBLE
            else $type = 0;                               # DECIMAL
          } elseif( $type == 'null' ) $type = 6;          # NULL
          elseif( $type == 'timestamp' ) $type = 7;       # TIMESTAMP
          elseif( $type == 'date' ) $type = 10;           # DATE
          elseif( $type == 'time' ) $type = 11;           # TIME
          elseif( $type == 'datetime' ) $type = 12;       # DATETIME
          elseif( $type == 'year' ) $type = 13;           # YEAR
          elseif( $type == 'blob' ) {
            if( $len > 65536 ) $type = 251;               # LONG BLOB
            elseif( $len > 255 ) $type = 252;             # BLOB
            else $type = 249;                             # TINY BLOB
          } elseif( $type == 'string' ) $type = 253;       # VARCHAR
          else 
            $type = 252;
          dump( $type );
          dump( $fl );
          dump( $len );
        }
        for( $i = 0; $i < $height; ++$i ) {
          $row = mysql_fetch_row( $result );
          for( $j = 0; $j < $width; ++$j ) 
            if( is_null($row[$j]) ) 
              dump( '' );
            else
              dump( ' '.$row[$j] );
        }
        mysql_free_result( $result );
      }
    }
  }
  mysql_free_result( mysql_query( $commit ? 'COMMIT' : 'ROLLBACK' ) );

} elseif( $_POST['server'] == 'pgsql' ) {

  $conn = '';
  if( $_POST['host'] != '' ) $conn .= "host=$_POST[host]";
  if( $_POST['port'] != '' ) $conn .= " port=$_POST[port]";
  if( $_POST['dbname'] != '' ) $conn .= " dbname=$_POST[dbname]";
  if( $_POST['user'] != '' ) $conn .= " user=$_POST[user]";
  if( $_POST['password'] != '' ) $conn .= " password=$_POST[password]";
  $conn = pg_connect( $conn ) || pgerr('some connection error');

  if( !$commit || array_key_exists( 'r2', $_POST ) ) 
    pg_free_result( pg_query( 'BEGIN' ) );
  $result = FALSE;
  for( $rn = 1; $rn < 1000; ++$rn ) {
    if( !array_key_exists( 'r'.$rn, $_POST ) )
      break;
    $data = 'OK:';
    $req = $_POST['r'.$rn];
    if( $req == 'connect' ) {
      dump( 0 );
      dump( 0 );
      dump( 0 );
    } elseif( substr($req,0,11) == 'blob_create' ) {
      list($oid) = sscanf( $req, 'blob_create %u' );
      pg_free_result( pg_query( $commit ? 'COMMIT' : 'ROLLBACK' ) );
      pg_free_result( pg_query( 'BEGIN' ) );
      $oid = pg_lo_create() or pgerr('lo_create failed');
      pg_free_result( pg_query( 'COMMIT' ) );
      pg_free_result( pg_query( 'BEGIN' ) );
      dump($oid);
    } elseif( substr($req,0,11) == 'blob_delete' ) {
      list($oid) = sscanf( $req, 'blob_delete %u' );
      $oid = pg_lo_unlink($oid) or pgerr('lo_unlink failed');
    } elseif( substr($req,0,10) == 'blob_write' ) {
      list($oid) = sscanf( $req, 'blob_write %s ' );
      $bin = substr($req,12+strlen($oid));
      $obj = pg_lo_open($oid,'w') or pgerr( 'lo_open failed' );
      $res = pg_lo_write($obj,$bin) or pgerr( 'lo_write failed' );
      pg_lo_close($obj);
      dump($res);
    } elseif( substr($req,0,9) == 'blob_read' ) {
      list($oid) = sscanf( $req, 'blob_read %u' );
      $obj = pg_lo_open($oid,'r') or pgerr( 'lo_open failed' );
      pg_lo_seek($obj,0,PGSQL_SEEK_END);
      $len = pg_lo_tell($obj);
      pg_lo_seek($obj,0,PGSQL_SEEK_SET);
      $res = pg_lo_read($obj,$len) or pgerr( 'lo_read failed' );
      pg_lo_close($obj);
      dump($res);
    } else {
      $result = pg_query($req) or pgerr("error at request: $req");
      if( pg_result_status($result) == PGSQL_COMMAND_OK ) {
        dump( 0 );
        dump( pg_affected_rows($result) );
        dump( pg_last_oid($result) );
        pg_free_result($result);
      } elseif( pg_result_status($result) == PGSQL_EMPTY_QUERY ) {
        dump( 0 );
        dump( 0 );
        pg_free_result($result);
      } elseif( pg_result_status($result) == PGSQL_TUPLES_OK ) {
        $width = pg_num_fields($result);
        $height = pg_num_rows($result);
        dump($width);
        dump($height);
        for( $i = 0; $i < $width; ++$i ) {
          $type = pg_field_type( $result, $i );
          dump( pg_field_name( $result, $i ) );
          dump( $type );
          dump( pg_field_size( $result, $i ) );
        }
        for( $i = 0; $i < $height; ++$i ) {
          $row = pg_fetch_row( $result );
          for( $j = 0; $j < $width; ++$j ) 
            if( is_null($row[$j]) ) 
              dump( '' );
            else
              dump( ' '.$row[$j] );
        }
        pg_free_result( $result );
      } else {
        $e = pg_result_error($result);
        pg_free_result($result);
        err( $e );
      }
    }
  }
  pg_free_result( pg_query( $commit ? 'COMMIT' : 'ROLLBACK' ) );

} else {
  err("server type '$_POST[server] is not supported");
}
if( $data != '' ) echo $data;
?>