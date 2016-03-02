<?php

session_start(); //Inicio la sesión

include_once('db_conexion.php');
include_once('php/php_global_variables.php');

header('Content-Disposition: attachment; filename="individuals_validated.txt"');
header('Content-type: text/plain');

$inversion_id=$_GET['id'];
$validation_study=$_GET['val'];
$method=$_GET['met'];
$region=$_GET['region'];
$population=$_GET['pop'];

if ($validation_study != ''){
	header('Content-Disposition: attachment; filename="individuals_validated.txt"');
}
elseif ($region!='' || $population!=''){
	header('Content-Disposition: attachment; filename="individuals_frequency.txt"');
}	

$sql_valInd="SELECT ind.code, ind.gender, ind.population, ind.family, ind.relationship, ind.panel, ind2.genotype , ind2.allele_comment, ind.allele_level, p.region
	FROM validation v
		INNER JOIN individuals_detection ind2 ON ind2.validation_id=v.id 
		INNER JOIN individuals ind ON ind2.individuals_id=ind.id 
		INNER JOIN population p ON ind.population=p.name ";
	
#if ($region!=''){$sql_valInd.='';}

$sql_valIndWhere=" WHERE v.inv_id='$inversion_id' ";

if ($validation_study != ''){
	$sql_valIndWhere.=" and v.research_name='$validation_study'";
}
if ($method!=''){
	$sql_valIndWhere.=" and v.method='$method'";
}	
if ($region!=''){
	$sql_valIndWhere.=" and p.region='$region'";
}
if ($population!=''){
	$sql_valIndWhere.=" and ind.population='$population'";
}	
$sql_valInd.=$sql_valIndWhere;

$result_valInd=mysql_query($sql_valInd);
$individuals='';

echo "code\tother_code\tgender\tpopulation\tregion\tfamily\trelationship\tgenotype\tpanel\tallele_comment\n";

while($indrow = mysql_fetch_array($result_valInd)){
	echo $indrow['code']."\t".$indrow['other code']."\t".$indrow['gender']."\t".$indrow['population']."\t".$indrow['region']."\t".$indrow['family']."\t".$indrow['relationship']."\t".$indrow['genotype']."\t".$indrow['panel']."\t".$indrow['allele_comment']."\n";
}

?>

