<?php
######################################
# PHP scraper
# Scraps data from EURES jobs
######################################

<<<<<<< local
=======
ini_set('max_execution_time', 0);

>>>>>>> other
include("config.php");

include("functions.php");

require 'scraperwiki/scraperwiki.php';

require 'scraperwiki/simple_html_dom.php';

include 'geocoderParser/GGeocoderParserLib.v1.php';

<<<<<<< local
$sql_job = mysql_query("SELECT id, url, country_id FROM job ") or die(mysql_error());
$cont_job = mysql_num_rows($sql_job);
echo $cont_job;
=======
$sql_job = mysql_query("SELECT id, url, country_id, source_id FROM job WHERE scraper_date IS NULL") or die(mysql_error());
>>>>>>> other

while($row_job = mysql_fetch_array($sql_job))
{
	$job_id = $row_job[0];		
	$url = $row_job[1];
	$country_id = $row_job[2];
<<<<<<< local
=======
	$source_id = $row_job[3];
>>>>>>> other

	$html = scraperwiki::scrape($url);

<<<<<<< local
=======
	$file = 'jobs/'.$job_id.'.html';
	$fh = fopen($file, 'w');
	fwrite($fh,$html);
	fwrite($fh,'<div id=url>'.$url.'</div>');
	fwrite($fh,'<div id=country_id>'.$country_id.'</div>');
	fwrite($fh,'<div id=source_id>'.$source_id.'</div>');
	fclose($fh);

>>>>>>> other
	$dom = new simple_html_dom();
	$dom->load($html);

	foreach($dom->find('th') as $data)
	{  

<<<<<<< local
	$text = trim($data->plaintext);     
=======
		$text = trim($data->plaintext);     
>>>>>>> other

<<<<<<< local
	$value = trim($data->next_sibling()->plaintext);
	$value = str_replace("'","\'",$value);
=======
		$value = trim($data->next_sibling()->plaintext);
		$value = str_replace("'","\'",$value);
>>>>>>> other

<<<<<<< local
	//FIXME. HACK by Lucas, to get the data from Maximum salary, since the TH for this data in EURES pages is not well-written, causing the extractor not to work. e.g. <th colspan="1">Maximum salary:</td> 
	if (preg_match('/Maximum salary:/', $text)){
		$maximum_salary = str_replace("Maximum salary:","",$text);
		$maximum_salary = trim(str_replace("</td>","",$maximum_salary));
		$maximum_salary = str_replace("'","\'",$maximum_salary);
	}
	else
	{
		switch($text) {
			//Summary
			case 'Title:': 
				$title = $value;
				$title_id = insert_name('title',$title);
				break;
			case 'Description:':break;
			case 'Required languages:':
				$required_languages = $value;
				$required_languages = str_replace("(","",$required_languages);	
				$required_languages = str_replace(")","",$required_languages);		
=======
		//FIXME. HACK by Lucas, to get the data from Maximum salary, since the TH for this data in EURES pages is not well-written, causing the extractor not to work. e.g. <th colspan="1">Maximum salary:</td> 
		if (preg_match('/Maximum salary:/', $text)){
			$maximum_salary = str_replace("Maximum salary:","",$text);
			$maximum_salary = trim(str_replace("</td>","",$maximum_salary));
			$maximum_salary = str_replace("'","\'",$maximum_salary);
		}
		else
		{
			switch($text) {
				//Summary
				case 'Title:': 
					$title = $value;
					$title_id = insert_name('title',$title);
					break;
				case 'Description:':break;
				case 'Required languages:':
					$required_languages = $value;
					$required_languages = str_replace("(","",$required_languages);	
					$required_languages = str_replace(")","",$required_languages);		
>>>>>>> other

<<<<<<< local
				$explode_language1 = explode(",",$required_languages);
=======
					$explode_language1 = explode(",",$required_languages);
>>>>>>> other

<<<<<<< local
				if (sizeof($explode_language1) == 1)
					$explode_language1 = explode(";",$required_languages);	
=======
					if (sizeof($explode_language1) == 1)
						$explode_language1 = explode(";",$required_languages);	
>>>>>>> other

<<<<<<< local
				$i = 0;
=======
					$i = 0;
>>>>>>> other

<<<<<<< local
				while ($i < sizeof($explode_language1))
				{
					$id_language = '';
					$id_language_level = '';
=======
					while ($i < sizeof($explode_language1))
					{
						$id_language = '';
						$id_language_level = '';
>>>>>>> other

<<<<<<< local
					$explode_language2 = explode("-",trim($explode_language1[$i]));
					$language = trim($explode_language2[0]); 
=======
						$explode_language2 = explode("-",trim($explode_language1[$i]));
						$language = trim($explode_language2[0]); 
>>>>>>> other

<<<<<<< local
					$language_id = insert_name('language',$language);
=======
						$language_id = insert_name('language',$language);
>>>>>>> other

<<<<<<< local
					if (sizeof($explode_language1) > 1)
					{
						$language_level = trim($explode_language2[1]);
=======
						if (sizeof($explode_language1) > 1)
						{
							$language_level = trim($explode_language2[1]);
>>>>>>> other

<<<<<<< local
						$language_level_id = insert_name('language_level',$language_level);
=======
							$language_level_id = insert_name('language_level',$language_level);
						}
						$i++;
						mysql_query("INSERT INTO job_language SET job_id = '$job_id', language_id ='$language_id', language_level_id ='$language_level_id'");
>>>>>>> other
					}
<<<<<<< local
					$i++;
					mysql_query("INSERT INTO job_language SET job_id = '$job_id', language_id ='$language_id', language_level_id ='$language_level_id'");
				}
				break;
			case 'Starting Date:':$starting_date = $value;break;
			case 'Ending date:':$ending_date = $value;break;
			//Geographical Information
			case 'Country:':$country = $value;break;    
			case 'Region:':
				$region = $value;
				if($region <> '' && $region <> '0' && $region <> '.')
					mysql_query("INSERT INTO region SET name ='$value', country_id = '$country_id'");
				$sql = mysql_query("SELECT id FROM region WHERE name = '$region' AND country_id = '$country_id'");				
				$row = mysql_fetch_array($sql);		
				$region_id = $row[0];
				break;
			//Salary / Contract
			case 'Minimum salary:':$minimum_salary = $value;break;
			case 'Maximum salary:':$maximum_salary = $value;break;
			case 'Salary currency:':
				$salary_currency = $value;
				$salary_currency_id = insert_name('salary_currency',$salary_currency);
				break;
			case 'Salary tax:':
				$salary_tax = $value;
				$salary_tax_id = insert_name('salary_tax',$salary_tax);
				break;
			case 'Salary period:':
				$salary_period = $value;
				$salary_period_id = insert_name('salary_period',$salary_period);
				break;
			case 'Hours per week:':$hours_per_week = $value;break;
			case 'Contract type:':
				$contract = $value;
				$contract = str_replace(")","",$contract);
=======
					break;
				case 'Starting Date:':$starting_date = $value;break;
				case 'Ending date:':$ending_date = $value;break;
				//Geographical Information
				case 'Country:':$country = $value;break;    
				case 'Region:':
					$region = $value;
					if($region <> '' && $region <> '0' && $region <> '.')
						mysql_query("INSERT INTO region SET name ='$value', country_id = '$country_id'");
					$sql = mysql_query("SELECT id FROM region WHERE name = '$region' AND country_id = '$country_id'");				
					$row = mysql_fetch_array($sql);		
					$region_id = $row[0];
					break;
				//Salary / Contract
				case 'Minimum salary:':
					$minimum_salary = $value;
					$salary = format_currency($minimum_salary);
					$minimum_salary = $salary['amount'];
					break;
				case 'Maximum salary:':
					$maximum_salary = $value;
					$salary = format_currency($maximum_salary);
					$maximum_salary = $salary['amount'];
					break;
				case 'Salary currency:':
					$salary_currency = $value;
					$salary_currency_id = insert_name('salary_currency',$salary_currency);
					break;
				case 'Salary tax:':
					$salary_tax = $value;
					$salary_tax_id = insert_name('salary_tax',$salary_tax);
					break;
				case 'Salary period:':
					$salary_period = $value;
					$salary_period_id = insert_name('salary_period',$salary_period);
					break;
				case 'Hours per week:':
					$hours_per_week = $value;
					format_hour($hours_per_week);
					break;
				case 'Contract type:':
					$contract = $value;
					$contract = str_replace(")","",$contract);
>>>>>>> other

<<<<<<< local
				if (strstr($contract, '('))
					$explode_contract = explode("(",$contract);
				elseif (strstr($contract, ' - '))
					$explode_contract = explode(" - ",$contract);
				else
					$explode_contract = explode("+",$contract);
=======
					if (strstr($contract, '('))
						$explode_contract = explode("(",$contract);
					elseif (strstr($contract, ' - '))
						$explode_contract = explode(" - ",$contract);
					else
						$explode_contract = explode("+",$contract);
>>>>>>> other

<<<<<<< local
				$contract_type = trim($explode_contract[0]);
=======
					$contract_type = trim($explode_contract[0]);
>>>>>>> other
	
<<<<<<< local
				$contract_type_id = insert_name('contract_type',$contract_type);
=======
					$contract_type_id = insert_name('contract_type',$contract_type);
>>>>>>> other

<<<<<<< local
				if (sizeof($explode_contract) > 1){
					$contract_hours = trim($explode_contract[1]);
=======
					if (sizeof($explode_contract) > 1){
						$contract_hours = trim($explode_contract[1]);
>>>>>>> other

<<<<<<< local
					$contract_hours_id = insert_name('contract_hours',$contract_hours);	
				}
				break;
			//Extras
			case 'Accommodation provided:':$accommodation_provided = $value;break;
			case 'Relocation covered:':$relocation_covered = $value;break;
			case 'Meals included:':$meals_included = $value;break;
			case 'Travel expenses:':$travel_expenses = $value;break;
			//Requirements
			case 'Education skills required:':
				$education_skills_required = $value;
				$education_skills_id = insert_name('education_skills',$education_skills_required);
				break;
			case 'Professional qualifications required:': $professional_qualifications_required = $value;break;
			case 'Experience required:':
				$experience_required = $value;
				$experience_id = insert_name('experience',$experience_required);
				break;
			case 'Driving license required:':
				$driving_license_required = $value;
				$driving_license_id = insert_name('driving_license',$driving_license_required);
				break;
			case 'Minimum age:': $minimum_age = $value;break;
			case 'Maximum age:': $maximum_age = $value;break;
			//Employer
			case 'Name:':$name = $value;break;
			case 'Information:':$information = $value;break;
			case 'Address:':$address = $value;break;                    
			case 'Phone:':$phone = $value;break;
			case 'Email:':$email = $value;break;
			case 'Fax:':$fax = $value;break;
			//Application
			case 'How to apply:':
				$how_to_apply = $value;
				$how_to_apply_id = insert_name('how_to_apply',$how_to_apply);
				break;
			case 'Contact:':$contact = $value;break;         
			case 'Last date for application:':$last_date_for_application = $value;break;
			//Other Information
			case 'Date published:':$date_published = $value;break;
			case 'National reference:':$national_reference = $value;break;
			case 'Eures reference:':$eures_reference = $value;break;
			case 'Last Modification Date:':$last_modification_date = $value;break;
			case 'Nace code:':$nace_code = $value;break;
			case 'ISCO code:':
				$isco_code = $value;
				switch(strlen ($isco_code)) {
					case 4: $isco_unit_code = $isco_code;$isco_minor_code = substr($isco_code, 0, 3);$isco_submajor_code= substr($isco_code, 0, 2);$isco_major_code = substr($isco_code, 0, 1);break;
					case 3: $isco_minor_code = $isco_code;$isco_submajor_code= substr($isco_code, 0, 2);$isco_major_code = substr($isco_code, 0, 1);break;
					case 2: $isco_submajor_code = $isco_code;$isco_major_code = substr($isco_code, 0, 1);break;
					case 1: $isco_major_code = $isco_code;break;
				}						
				break;
			case 'Number of posts:':$number_of_posts = $value;break;
			//FIXME. HACK by Lucas, to get the data from Contact, since the TH for this data in EURES pages is not well-written, causing the extractor not to work. e.g.<th colspan="1>Contact:</th>
			default:$contact = trim(str_replace("</td>","",$text));$contact = str_replace("'","\'",$contact);break; 
=======
						$contract_hours_id = insert_name('contract_hours',$contract_hours);	
					}
					break;
				//Extras
				case 'Accommodation provided:':$accommodation_provided = $value;break;
				case 'Relocation covered:':$relocation_covered = $value;break;
				case 'Meals included:':$meals_included = $value;break;
				case 'Travel expenses:':$travel_expenses = $value;break;
				//Requirements
				case 'Education skills required:':
					$education_skills_required = $value;
					$education_skills_id = insert_name('education_skills',$education_skills_required);
					break;
				case 'Professional qualifications required:': $professional_qualifications_required = $value;break;
				case 'Experience required:':
					$experience_required = $value;
					$experience_id = insert_name('experience',$experience_required);
					break;
				case 'Driving license required:':
					$driving_license_required = $value;
					$driving_license_id = insert_name('driving_license',$driving_license_required);
					break;
				case 'Minimum age:': $minimum_age = $value;break;
				case 'Maximum age:': $maximum_age = $value;break;
				//Employer
				case 'Name:':$name = $value;break;
				case 'Information:':$information = $value;break;
				case 'Address:':$address = $value;break;                    
				case 'Phone:':$phone = $value;break;
				case 'Email:':$email = $value;break;
				case 'Fax:':$fax = $value;break;
				//Application
				case 'How to apply:':
					$how_to_apply = $value;
					$how_to_apply_id = insert_name('how_to_apply',$how_to_apply);
					break;
				case 'Contact:':$contact = $value;break;         
				case 'Last date for application:':$last_date_for_application = $value;break;
				//Other Information
				case 'Date published:':$date_published = $value;break;
				case 'National reference:':$national_reference = $value;break;
				case 'Eures reference:':$eures_reference = $value;break;
				case 'Last Modification Date:':$last_modification_date = $value;break;
				case 'Nace code:':$nace_code = $value;break;
				case 'ISCO code:':
					$isco_code = $value;
					switch(strlen ($isco_code)) {
						case 4: $isco_unit_code = $isco_code;$isco_minor_code = substr($isco_code, 0, 3);$isco_submajor_code= substr($isco_code, 0, 2);$isco_major_code = substr($isco_code, 0, 1);break;
						case 3: $isco_minor_code = $isco_code;$isco_submajor_code= substr($isco_code, 0, 2);$isco_major_code = substr($isco_code, 0, 1);break;
						case 2: $isco_submajor_code = $isco_code;$isco_major_code = substr($isco_code, 0, 1);break;
						case 1: $isco_major_code = $isco_code;break;
					}						
					break;
				case 'Number of posts:':$number_of_posts = $value;break;
				//FIXME. HACK by Lucas, to get the data from Contact, since the TH for this data in EURES pages is not well-written, causing the extractor not to work. e.g.<th colspan="1>Contact:</th>
				default:$contact = trim(str_replace("</td>","",$text));$contact = str_replace("'","\'",$contact);break; 
			}
>>>>>>> other
		}
	}
<<<<<<< local
	}
=======
>>>>>>> other

	## CLEANING SOME DATA ##
<<<<<<< local
=======

	if (isset($salary['currency']) && !isset($salary_currency))
	{
		$salary_currency = $salary['currency'];
		$salary_currency_id = insert_name('salary_currency',$salary_currency);
	}
	if (isset($salary['currency']) && !isset($salary_period))
	{
		$salary_period = $salary['period'];
		$salary_period_id = insert_name('salary_period',$salary_period);
	}

>>>>>>> other
	if (preg_match('/www./',$address))
	{
		$explode_address = explode(",",$address);

		$i = 0;

		while ($i < sizeof($explode_address))
		{
			if (preg_match('/www./', $explode_address[$i]))
			{
				$homepage = $explode_address[$i];
			}
			$i++;
		}
	}
<<<<<<< local

=======
	
>>>>>>> other
	if (preg_match('/www./',$information))
	{
<<<<<<< local
		$homepage = $information;
=======
		$explode_information = explode(" ",$information);

		$i = 0;

		while ($i < sizeof($explode_information))
		{
			if (preg_match('/www./', $information[$i]))
			{
				$homepage = $information[$i];
			}
			$i++;
		}
>>>>>>> other
		$information = NULL;
	}
<<<<<<< local
=======


>>>>>>> other
	elseif (preg_match('/@/',$information))
	{
		if ($email == '')
			$email = $information;
		$information = NULL;
	}

	if ($name == "siehe Beschreibung")
	{
		$name = NULL;
	}

	if (preg_match('/@/',$name))
	{
		if ($email == '')
			$email = $name;
		$name = NULL;
	}
/*
	if (preg_match('/@/',$how_to_apply))
	{
		if ($email == '')
			$email = $how_to_apply;
		$how_to_apply = NULL;
	}	
*/
<<<<<<< local
=======
	if (isset($homepage))
		$homepage = addhttp($homepage);
>>>>>>> other

	## CLEANING ADDRESS ##

	if ($address <> '')
		$address_array = get_address($address);

	if ($address_array['country_id'] == '')
	{
		$address_array['country_id'] = $country_id;
	}

	## INSERTING EMPLOYER AND AVOIDING SOME DUPLICATES ##
	// Conditions for avoiding duplicates must be tested for more jobs to avoid wrong relations. Maybe we should take them off for now ?	

	if ($name <> '' || ($address <> '' && $address <> ',  ,'))
	{
		$query = "SELECT id FROM employer WHERE name = '$name' AND address = '$address' AND country_id = '$address_array[country_id]'";
	
		$employer_id = select_id($query);

		if ($employer_id == '')
		{
			if ($name <> '' && $address_array['formatted_address'] <> '')
				$query = "SELECT id FROM employer WHERE name = '$name' AND formatted_address = '$address_array[formatted_address]' AND country_id = '$address_array[country_id]'";
			$employer_id = select_id($query);
		}

		if ($employer_id == '')
		{
			if ($address_array['formatted_address'] <> '' && $address_array['route'] <> '' && $address_array['administrative_area_level_1'] <> '' && $address_array['administrative_area_level_2']<> '')
				$query = "SELECT id FROM employer WHERE formatted_address = '$address_array[formatted_address]' AND route = '$address_array[route]' AND country_id = '$address_array[country_id]' AND administrative_area = '$address_array[administrative_area_level_1]' AND subadministrative_area = '$address_array[administrative_area_level_2]'";
			$employer_id = select_id($query);
		}

		if ($employer_id == '')
		{
			if ($name <> '' && $address_array['route'] <> '' && $address_array['administrative_area_level_1'] <> '' && $address_array['administrative_area_level_2']<> '')
				$query = "SELECT id FROM employer WHERE name = '$name' AND route = '$address_array[route]' AND country_id = '$address_array[country_id]' AND administrative_area = '$address_array[administrative_area_level_1]' AND subadministrative_area = '$address_array[administrative_area_level_2]'";
			$employer_id = select_id($query);
		}

		if ($employer_id == '')
		{
			if ($name <> '' && $address_array['postal_code'] <> '' && $address_array['administrative_area_level_1'] <> '' && $address_array['administrative_area_level_2']<> '')
				$query = "SELECT id FROM employer WHERE name = '$name' AND postal_code = '$address_array[postal_code]' AND country_id = '$address_array[country_id]' AND administrative_area = '$address_array[administrative_area_level_1]' AND subadministrative_area = '$address_array[administrative_area_level_2]'";
			$employer_id = select_id($query);
		}			

		if ($employer_id == '')
		{
			$query = "INSERT INTO employer SET 
				name = '$name',
				homepage = '$homepage',
				address ='$address',
				formatted_address ='$address_array[formatted_address]',
				country_id ='$address_array[country_id]',
				administrative_area ='$address_array[administrative_area_level_1]',
				subadministrative_area ='$address_array[administrative_area_level_2]',
				locality ='$address_array[locality]',
				route ='$address_array[route]',
				street_number ='$address_array[street_number]',
				postal_code ='$address_array[postal_code]',
				latitude ='$address_array[latitude]',
				longitude ='$address_array[longitude]',
				lat_southwest ='$address_array[viewport_lat_southwest]',
				lng_southwest ='$address_array[viewport_lng_southwest]',
				lat_northeast ='$address_array[viewport_lat_northeast]',
				lng_northeast ='$address_array[viewport_lng_northeast]',
				url = '$url',
				scraper_date = SYSDATE(),	 
				scraper_hour = SYSDATE()";
			mysql_query($query) or die (mysql_error()); 
			$employer_id = select_id("SELECT LAST_INSERT_ID() FROM employer");
		}
			
	}

	## INSERTING CONTACT AND AVOIDING SOME DUPLICATES ##
	if ($contact <> '' || $email <> '' || $information <> '' || $phone <> '' || $fax <> '')
	{
		if ($email <> '')
			$query = "SELECT id FROM contact WHERE email = '$email'";
		elseif ($information <> '' && $phone <> '' && $fax <> '') 
			$query = "SELECT id FROM contact WHERE information LIKE '$information' AND fax LIKE '$fax' AND phone LIKE '$phone' AND country_id = '$address_array[country_id]'";
		elseif ($information <> '' && $phone <> '')  
			$query = "SELECT id FROM contact WHERE information LIKE '$information' AND phone LIKE '$phone' AND country_id = '$address_array[country_id]'";
		elseif ($information <> '' && $fax <> '') 
			$query = "SELECT id FROM contact WHERE information LIKE '$information' AND fax LIKE '$fax' AND country_id = '$address_array[country_id]'";
		elseif ($phone <> '' && $fax <> '') 
			$query = "SELECT id FROM contact WHERE phone LIKE '$phone' AND fax LIKE '$fax' AND country_id = '$address_array[country_id]'";
		else
			$query = "SELECT id FROM contact WHERE email = '$email' AND information LIKE '$information' AND phone LIKE '$phone' AND fax LIKE '$fax' AND country_id = '$address_array[country_id]'";

		$contact_id = select_id($query);

		if ($contact_id == '')
		{
			mysql_query("INSERT INTO contact SET 
					employer_id = '$employer_id',
					contact = '$contact',
					information = '$information',
					phone = '$phone',
					email='$email',
					fax = '$fax',
					country_id ='$address_array[country_id]',						
					url = '$url',
					scraper_date = SYSDATE(),	 
					scraper_hour = SYSDATE()"
			) or die (mysql_error());
			$contact_id = select_id("SELECT LAST_INSERT_ID() FROM contact");
		}
	}

	## UPDATING JOB ##
	mysql_query("UPDATE job SET 
			employer_id = ".db_prep($employer_id).",
			contact_id = ".db_prep($contact_id).",    
			title_id = ".db_prep($title_id).",       
			starting_date = STR_TO_DATE('$starting_date', '%d/%m/%Y'),
			ending_date = STR_TO_DATE('$ending_date', '%d/%m/%Y'),
			country_id = ".db_prep($country_id).",  
			region_id = ".db_prep($region_id).",  
			minimum_salary = ".db_prep($minimum_salary).",  
			maximum_salary = ".db_prep($maximum_salary).",  
			salary_currency_id = ".db_prep($salary_currency_id).",  
			salary_tax_id = ".db_prep($salary_tax_id).",  
			salary_period_id = ".db_prep($salary_period_id).",   
			hours_per_week = ".db_prep($hours_per_week).",  
			contract_type_id = ".db_prep($contract_type_id).",   
			contract_hours_id = ".db_prep($contract_hours_id).",   
			accommodation_provided = ".db_prep($accommodation_provided).",   
			relocation_covered = ".db_prep($relocation_covered).",   
			meals_included = ".db_prep($meals_included).",   
			travel_expenses = ".db_prep($travel_expenses).",   
			education_skills_id = ".db_prep($education_skills_id).",   
			professional_qualifications_required = ".db_prep($professional_qualifications_required).",   
			experience_id = ".db_prep($experience_id).",   
			driving_license_id = ".db_prep($driving_license_id).",   
			minimum_age = ".db_prep($minimum_age).",   
			maximum_age = ".db_prep($maximum_age).",   
			how_to_apply_id = ".db_prep($how_to_apply_id).",   
			last_date_for_application = STR_TO_DATE('$last_date_for_application', '%d/%m/%Y'),
			date_published = STR_TO_DATE('$date_published', '%d/%m/%Y'),
			last_modification_date = STR_TO_DATE('$last_modification_date', '%d/%m/%Y'),
			nace_code = ".db_prep($nace_code).",   
			national_reference = ".db_prep($national_reference).",   
			eures_reference = ".db_prep($eures_reference).",   
			isco_code = ".db_prep($isco_code).",   
			isco_unit_code = ".db_prep($isco_unit_code).",   
			isco_minor_code = ".db_prep($isco_minor_code).",   
			isco_submajor_code = ".db_prep($isco_submajor_code).",   
			isco_major_code = ".db_prep($isco_major_code).",   
			number_of_posts = ".db_prep($number_of_posts).",   
<<<<<<< local
			scraper_date = SYSDATE(),	 
			scraper_hour = SYSDATE()
=======
			job_scraper_date = SYSDATE(),	 
			job_scraper_hour = SYSDATE()
>>>>>>> other
		
		WHERE url = '$url'") or die(mysql_error());

	## WAITING AND CLEARING MEMORY ##
	if ($cont_job > 1){
		sleep(0.5);
		unset($title,$required_languages,$starting_date,$ending_date,$country,$region,$minimum_salary,$maximum_salary,$salary_currency,$salary_tax,$salary_period,$hours_per_week,$contract,$contract_type,$contract_hours, 			$accommodation_provided,$relocation_covered,$meals_included,$travel_expenses,$education_skills_required,$professional_qualifications_required,$experience_required,$driving_license_required,$minimum_age,$maximum_age,
		$name,$information,$address,$phone,$email,$fax,$how_to_apply,$contact,$last_date_for_application,$date_published,$national_reference,$last_modification_date,$nace_code,$isco_code,$isco_unit_code,$isco_minor_code, 			$isco_submajor_code,$isco_major_code,$number_of_posts,$other_value,$eures_reference,$contract_type_id,$contract_hours_id,$education_skills_id,$experience_id,$driving_license_id,$contact_id,$employer_id,		
<<<<<<< local
		$address_array,$how_to_apply_id,$title_id,$homepage,$dom,$text,$data,$sql,$query,$row,$salary_currency_id,$salary_period_id,$salary_tax_id);
=======
		$address_array,$how_to_apply_id,$title_id,$homepage,$dom,$text,$data,$sql,$query,$row,$salary_currency_id,$salary_period_id,$salary_tax_id,$salary,$salary['amount']);
>>>>>>> other
	}
}

mysql_query("INSERT INTO update_service SET date = SYSDATE(), hour = SYSDATE(), type='job'");

?>
