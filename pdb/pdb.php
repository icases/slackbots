<html>

<?php
	$token= $_GET['text'];
	//$reponse_url=$_GET['response_url']
	#check token
	//$query= 'P50225'
	$query= $_GET['text'];
	#filter and validate PDB or Uniprot
	#uniprot RegExp
	# [OPQ][0-9][A-Z0-9]{3}[0-9]|[A-NR-Z][0-9]([A-Z][A-Z0-9]{2}[0-9]){1,2}
	
	
	$xml="<?xml version='1.0' encoding='UTF-8'?>
			 <orgPdbQuery>    
		    	<queryType>org.pdb.query.simple.UpAccessionIdQuery</queryType>
		    	<description>Simple query for a list of UniprotKB Accession IDs: $query</description>   
		    	<accessionIdList>$query</accessionIdList>
				</orgPdbQuery>";
	//echo $xml;
	$ch = curl_init("http://www.rcsb.org/pdb/rest/search/");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
	curl_setopt($ch, CURLINFO_CONTENT_TYPE, 'application/x-www-form-urlencoded');
	$data=curl_exec($ch);
	curl_close($ch);
?>
<head>
	<title>PDB Structures for<?php echo $query ?></title>
</head>
<body>
		<?= $data?>
</body>
	