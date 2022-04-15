#!/usr/bin/php
<?php
error_reporting(0);

if( !filter_var( $argv[ 1 ], FILTER_VALIDATE_IP ) )
{
  die( "Invalid IP address.\n" );
}

if( strlen( $argv[ 2 ] ) != 2 )
{
	die( "Second argument must be a valid hex value.\n" );
}

// This returns a hex string that we have to parse
$thisOID = "1.3.6.1.4.1.2435.2.3.9.4.2.1.5.5.8.0";

$thisObject = snmp2_get( $argv[ 1 ], "public", $thisOID );

$thisResult = explode( ":", str_replace( array( " ", "\n" ), "", $thisObject ) );

$myHexVals = explode( "\n", chunk_split( $thisResult[ 1 ], 14 ) );

while( list( $key, $val ) = each( $myHexVals ) )
{
	$thisMetric = substr( $val, 0, 2 );
	$thisMetricVal = substr( $val, 6 );

	if( $thisMetric != "" )
		$theseVals[ $thisMetric ] =  hexdec( $thisMetricVal );
}

echo trim( $theseVals[ $argv[ 2 ] ] );
