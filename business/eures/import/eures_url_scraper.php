<?php
######################################
# PHP scraper
# Scraps the URLs for EURES jobs
######################################

<<<<<<< local
=======
ini_set('max_execution_time', 0);

>>>>>>> other
include("config.php");

include("functions.php");

require 'scraperwiki/scraperwiki.php';

require 'scraperwiki/simple_html_dom.php';

function scraper($url_search, $country_id)
{
	$has_next = false;
	$base_url = "http://ec.europa.eu/eures/eures-searchengine/servlet";

	$html = scraperwiki::scrape($url_search);  

	$dom = new simple_html_dom();
	$dom->load($html);

	foreach($dom->find('table[class=JResult]') as $result)
	{
		foreach($result->find('td[class=JRTitle] a') as $job_page)
		{
			$chars = explode("'",$job_page->onclick);

			$url_job = $base_url.substr($chars[1], 1);

			$url_id = strstr($url_job, 'uniqueJvId=');

			$url_id = str_replace('uniqueJvId=',"",$url_id);

			echo "JOB: " .$url_job . "<br />";
		};

		foreach($result->find('th') as $data)
		{   
			$text = trim($data->plaintext);

			if ($text == 'Description:')
			{
				$description = trim($data->next_sibling()->plaintext);
				echo "DESCRIPTION: " .$description. "<br />";    
			}

			if ($text == 'Source:')
			{
				$source = trim($data->next_sibling()->plaintext);
				$source = str_replace("'","\'",$source);
				if ($source <> '' && $source <> '&nbsp;'){
					$source_id = insert_name('source',$source);	
					echo "SOURCE: " .$source. "<br /><br />";   
				}
			}
		}
		$description = str_replace("'","\'",$description);
<<<<<<< local
=======
		$description = str_replace("</BR>","",$description);
>>>>>>> other

		$sql = mysql_query("SELECT * FROM job WHERE url = '$url_job'");
		$cont = mysql_num_rows($sql);
		if ($cont == 0)
<<<<<<< local
			mysql_query("INSERT INTO job SET url = '$url_job', url_id = '$url_id', description = '$description', source_id = '$source_id', url_search = '$url_search', country_id='$country_id'");
=======
			mysql_query("INSERT INTO job SET 
					url = '$url_job', 
					url_id = '$url_id', 
					description = '$description', 
					source_id = '$source_id', 
					url_search = '$url_search', 
					country_id='$country_id',
					url_scraper_date = SYSDATE(),	 
					url_scraper_hour = SYSDATE()");
>>>>>>> other
		else
			echo "Job URL already extracted: ".$url_job."<br /><br />";
	}

	foreach($dom->find('div[class=prevNext] a') as $next_page)
	{
		$text = $next_page->plaintext;

		if ($text == "Next page")
		{
			$url_next = substr($next_page->href, 1);

			$url_next = $base_url.$url_next;

			$has_next = true;
<<<<<<< local
=======

			print "<br /><br />NEXT: " . $url_next . "<br /><br />";
>>>>>>> other
		}   
<<<<<<< local
		print "<br /><br />NEXT: " . $url_next . "<br /><br />";
=======
>>>>>>> other
	};

<<<<<<< local
=======
	unset($html,$dom,$result,$job_page,$data,$next_page,$text,$url_id,$url_job,$description,$source,$source_id,$url_search);

>>>>>>> other
//Comment this for tests, uncomment this to get all data
//	if ($has_next == true){
<<<<<<< local
		sleep(1);
		unset($html,$dom,$result,$job_page,$data,$next_page,$text,$url_id,$url_job,$description,$source,$source_id,$url_search);
=======
//		sleep(1);
>>>>>>> other
//		scraper($url_next, $country_id);
//	}
}



//Comment this for tests, uncomment this to get all data
<<<<<<< local
$country = array('AT', 'BE','BG','CY','CZ','DK','EE','FI','FR','DE','GR','HU','IS','IR','IT','LV','LI','LT','LU','MT','NL','NO','PL','PT','RO','SK','SI','ES','SE','CH','UK');
=======
$country = array('GR');
>>>>>>> other
//$country = array('AT');

$page_size = 99;

$sql_update = mysql_query("SELECT * FROM update_service");

$count_update = mysql_num_rows($sql_update);		

<<<<<<< local
if ($count_update > 0)
{
	$day = date("d") - 1;
=======
$day = "01";
>>>>>>> other

<<<<<<< local
	$month = date("m");
=======
$month = "01";
>>>>>>> other

<<<<<<< local
	$year = "1975";
}
else
{
	$day = "01";

	$month = "01";

	$year = "1975";
}
=======
$year = "1975";
>>>>>>> other

for ($i=0; $i < sizeof($country); $i++)
{
	$url_first = "http://ec.europa.eu/eures/eures-searchengine/servlet/BrowseCountryJVsServlet?lg=EN&isco=&country=".$country[$i]."&multipleRegions=%25&date=".$day."%2F".$month."%2F".$year."&title=&durex=&exp=&qual=&pageSize=".$page_size."&totalCount=999999999&startIndexes=0-1o1-1o2-1I0-2o1-30o2-1I0-3o1-59o2-1I0-4o1-88o2-1I&page=1";
	scraper($url_first, $country[$i]);
}

mysql_query("INSERT INTO update_service SET date = SYSDATE(), hour = SYSDATE(),type='url'");

?>
