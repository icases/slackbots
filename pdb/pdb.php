<?php
	$config = parse_ini_file("pdb.ini");
	$token= $_GET['token'];
	//check token
	if($token!=$config['token']){
		http_response_code(401); 
		exit("<h1>ERROR 401:Unauthorized</h1><p>You are not authorized to use this service<p>\n");
		
	}
	//$reponse_url=$_GET['response_url']
	#
	//$query= 'P50225'
	$query= $_GET['text'];
	#filter and validate PDB or Uniprot
	
	#uniprot RegExp
	if(preg_match_all("/([OPQ][0-9][A-Z0-9]{3}[0-9]|[A-NR-Z][0-9]([A-Z][A-Z0-9]{2}[0-9]){1,2})/",$query,$uni_ids)>0){
		//print_r($uni_ids[1]);
		foreach ($uni_ids[1] as $id){	
			$xml="<?xml version='1.0' encoding='UTF-8'?>
			 <orgPdbQuery>    
		    	<queryType>org.pdb.query.simple.UpAccessionIdQuery</queryType>
		    	<description>Simple query for a list of UniprotKB Accession IDs: $id</description>   
		    	<accessionIdList>$id</accessionIdList>
				</orgPdbQuery>";
			//echo $xml;
			$ch = curl_init("http://www.rcsb.org/pdb/rest/search/");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_POST,1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
			curl_setopt($ch, CURLINFO_CONTENT_TYPE, 'application/x-www-form-urlencoded');
			
			$data=curl_exec($ch);
			preg_match_all('/(\w+):\d\s/',$data,$ids);
			$res[$id]=$ids[1];
			curl_close($ch);
		}
	} else {
		$data="$query no parece un Uniprot ID";
	}	
if ($_GET['team_domain']==FALSE){
?>
<html>
<head>
	<title>PDB Structures for <?php echo $query ?></title>
</head>
<body>
		
		<?php foreach($res as $id => $pdbs) { ?>
			
			<h1>Structures for <?php echo $id ?></h1>

			<ul>
			<?php
			foreach($pdbs as $pdb){
			?>
			<li><a href='http://www.rcsb.org/pdb/explore/jmol.do?structureId=<?=$pdb?>' target='_blank'><?=$pdb?></a></li>
			<?php	
			}
			?>
			</ul>
		<?php
		}
		?>
		
</body>
<?php } else {
	header("Content-type:text/plain");
	foreach($res as $id => $pdbs) { ?>
	*Structures for <?=$id?>*\n
	<?php	foreach($pdbs as $pdb){ ?>
	- <http://www.rcsb.org/pdb/explore/jmol.do?structureId=<?=$pdb?>|<?=$pdb?>>\n
  <?php }
	}
 }?>