

<?php
	$my_token="fLunbvOkhS3J5CLnkiGi0PZd";
	$token= $_GET['text'];
	//check token
	if($token!=$my_token){
		http_response_code(401); 
	}
	//$reponse_url=$_GET['response_url']
	#
	//$query= 'P50225'
	$query= $_GET['text'];
	#filter and validate PDB or Uniprot
	
	#uniprot RegExp
	if(preg_match("/^[OPQ][0-9][A-Z0-9]{3}[0-9]|[A-NR-Z][0-9]([A-Z][A-Z0-9]{2}[0-9]){1,2}$/",$query)===1){
		$xml="<?xml version='1.0' encoding='UTF-8'?>
			 <orgPdbQuery>    
		    	<queryType>org.pdb.query.simple.UpAccessionIdQuery</queryType>
		    	<description>Simple query for a list of UniprotKB Accession IDs: $query</description>   
		    	<accessionIdList>$query</accessionIdList>
				</orgPdbQuery>";
	
		$ch = curl_init("http://www.rcsb.org/pdb/rest/search/");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		curl_setopt($ch, CURLINFO_CONTENT_TYPE, 'application/x-www-form-urlencoded');

		$data=curl_exec($ch);
		preg_match_all('/(\w+):\d\s/',$data,$ids);
		curl_close($ch);
	} else {
		$data="$query no parece un Uniprot ID";
	}	
?>
<html>
<head>
	<title>PDB Structures for <?php echo $query ?></title>
</head>
<body>
		<h1>Structures for <?php echo $query ?></h1>
		<?php echo $data?>
		<ul>
		<?php
		foreach($ids[1] as $id){
		?>
		<li><a href='http://www.rcsb.org/pdb/explore/jmol.do?structureId=<?=$id?>' target='_blank'><?=$id?></a></li>
		<?php	
		}
		?>
		</ul>
</body>
	