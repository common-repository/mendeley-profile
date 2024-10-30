<?php

/*
Plugin Name: mendeley-profile
Plugin URI: http://mblazquez.es/mendeley-profile
Description: Displays the profile information, publications and curriculum vitae from Mendeley to your wordpress website
Version: 1.0
Author: mblazquez.es
Author URI: http://www.mblazquez.es/
License: GPL2
*/

/*  
Copyright 2014 Manuel Blázquez-Ochando (email: manublaz@ucm.es)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/



// =============================================//
// DISPLAY MENDELEY PROFILE PLUGIN            	//
// =============================================//
function mendeleyProfile(){
	$url = get_option(urlmendeley);
	$lengthurl = strlen($url);
	if($url != "" && $lengthurl >= "15"){ 

		// ======================================================================//
		// EXTRACTOR                                                             //
		// ======================================================================//
		$searchpage = file_get_contents($url);
		$searchpage = htmlspecialchars_decode(utf8_decode(htmlentities($searchpage, ENT_COMPAT, 'utf-8', false)));
		$searchpage = preg_replace("/<a href=\"\/disciplines/", "<a href=\"http://www.mendeley.com/disciplines", $searchpage);
		$searchpage = preg_replace("/<a /", "<a target='_blank' ", $searchpage); 
		$doc = new DOMDocument();
		@$doc->loadHTML("$searchpage");
		$xpath = new DOMXPath($doc);
		// ======================================================================//
		
		
		// ======================================================================//
		// STYLE AND META                                                        //
		// ======================================================================//
		echo "	
		<style>
			.mendeleypowered {
				float: right; line-height: 81px; 
				font-family: arial; font-size: 0.75em; font-style: italic; color: #808080;
			}
			.mendeleyline { width: 100%; height: 3px; clear: both; background-color: #a40f24; }
			.plugin_mendeley_workswith { font-family: arial; margin: 0px; padding: 0px; }
			.plugin_mendeley_item { 
				margin-left: 50px; margin-bottom: 10px; padding: 20px; text-align: justify; 
				font-family: arial; font-size: 0.85em; font-style: normal; color: #404040; 
				background-color: #f5f5f5; list-style: none; 
			}
			.clear { width: 100%; height: 1px; clear: both; }
			.clearline { width: 100%; height: 1px; clear: both; }
			.clearline20 { width: 100%; height: 20px; clear: both; }
			.clearline30 { width: 100%; height: 30px; clear: both; }
			#profile_photo { padding: 20px; float: left; background-color: #f1f1f1; }
				
			.profile_main_info_right { 
				width: -moz-calc(100% - 200px); width: calc(100% - 200px); float: left; padding: 20px; 
				font-family: arial; font-size: 0.95em; font-style: normal; color: #404040; 
			}
			.location_name { clear: both; font-family: arial; font-weight: bold; color: #363636; }
			.underlined { 
				font-family: arial; font-size: 1.3em; font-style: normal; 
				color: #202020; text-shadow: #e9e9e9 1px 1px 1px; 
			}
			.underlined_biographical { 
				padding-left: 20px; padding-top: 6px; padding-bottom: 5px; margin-bottom: 0px; 
				font-family: arial; font-size: 1.2em; font-style: normal; color: #202020; 
				text-shadow: #d9d9d9 1px 1px 1px; background-color: #f1f1f1; 
			}
			.clearline_biographical { width: 100%; height: 3px; margin-bottom: 16px; clear: both; background-color: #E1E8E9; }
			
			.underlined_publications { 
				padding-left: 20px; padding-top: 6px; padding-bottom: 5px; margin-bottom: 0px; 
				font-family: arial; font-size: 1.2em; font-style: normal; color: #202020; 
				background-color: #f1f1f1; text-shadow: #d9d9d9 1px 1px 1px; 
			}
			.clearline_publications { width: 100%; height: 3px; margin-bottom: 16px; clear: both; background-color: #D2DDDE; }
			.underlined_awards { 
				padding-left: 20px; padding-top: 6px; padding-bottom: 5px; margin-bottom: 0px; 
				font-family: arial; font-size: 1.2em; font-style: normal; color: #202020; 
				background-color: #f1f1f1; text-shadow: #d9d9d9 1px 1px 1px; 
			}
			.clearline_awards { width: 100%; height: 3px; margin-bottom: 16px; clear: both; background-color: #C4D2D4; }
			.underlined_professional { 
				padding-left: 20px; padding-top: 6px; padding-bottom: 5px; margin-bottom: 0px; 
				font-family: arial; font-size: 1.2em; font-style: normal; color: #202020; 
				background-color: #f1f1f1; text-shadow: #d9d9d9 1px 1px 1px; 
			}
			.clearline_professional { width: 100%; height: 3px; margin-bottom: 16px; clear: both; background-color: #B5C7C9; }
			.underlined_education { 
				padding-left: 20px; padding-top: 6px; padding-bottom: 5px; margin-bottom: 0px; 
				font-family: arial; font-size: 1.2em; font-style: normal; color: #202020; 
				background-color: #f1f1f1; text-shadow: #d9d9d9 1px 1px 1px; 
			}
			.clearline_education { width: 100%; height: 3px; margin-bottom: 16px; clear: both; background-color: #A6BCBE; }
			.bold { font-family: arial; font-weight: bold; font-style: normal; color: #363636;}
			.text-minor { font-family: arial; line-height: 20px; font-size: 0.85em; font-style: italic; color: #808080; }
			.left { font-family: arial; clear: both; font-size: 0.85em; font-style: italic; color: #808080; }
			.right { font-family: arial; float: left; }
			span a { font-family: arial; color: #224a6a; font-size: 0.95em; text-decoration: none; }
			span a:hover { font-family: arial; color: #4497da; font-size: 0.95em; text-decoration: underline; }
				
			a.black { font-family: arial; color: #224a6a; font-size: 0.95em; text-decoration: none; }
			a.black:hover { font-family: arial; color: #4497da; font-size: 0.95em; text-decoration: underline; }
			a.light	{ font-family: arial; color: #224a6a; font-size: 0.95em; text-decoration: none; }
			a.light:hover { font-family: arial; color: #4497da; font-size: 0.95em; text-decoration: underline; }
			a.red { font-family: arial; color: #224a6a; font-size: 0.95em; text-decoration: none; }
			a.red:hover { font-family: arial; color: #4497da; font-size: 0.95em; text-decoration: underline; }
		</style>
		";
		// ======================================================================//
		
	
		// PROFILE USER _____________________________________________
		// ==========================================================>>>
		if(get_option(profile) == "on") {

			// EXTRACT :: PROFILE DATA
			// =========================================================================================================================>>>
			$pfiliation = $xpath->query("/html/body//div[@class='profile_main_info_right']");
			$pfil = $doc->saveHTML($pfiliation->item(0));
		
			// Profile data with photo integrated
			if(get_option(profile_photo) == "on"){

				// EXTRACT :: PROFILE PHOTO
				// =========================================================================================================================>>>
				$photodata = $xpath->query("/html/body//div[@id='profile_photo']");
				$photo = $doc->saveHTML($photodata->item(0));
				echo "
				<div>$photo $pfil</div>
				<div class='clearline'></div>
				";
				
			}
			
			// Profile data only
			else {
				echo "
				<div>$pfil</div>
				<div class='clearline'></div>
				";
			}
		}
		// ==========================================================>>>
		
		// BIOGRAPHICAL INFORMATION _________________________________
		// ==========================================================>>>
		if(get_option(biograph) == "on"){
			echo "<div class='underlined_biographical'>Biographical Information</div>";
			echo "<div class='clearline_biographical'></div>";
			$biographical = $xpath->query("/html/body//div[@id='biographical_info_show']")->item(0)->nodeValue;
			echo "<div class='plugin_mendeley_item'>$biographical</div>";
		}
		// ==========================================================>>>
		// JOURNAL ARTICLE __________________________________________
		// ==========================================================>>>
		if(get_option(publications_journalarticle) == "on"){
			echo "<div class='underlined_publications'>Journal Article</div>";
			echo "<div class='clearline_publications'></div>";
			$publications = $xpath->query("/html/body//ul[@id='JournalArticle']/li/div");
			for($i=0; $i<$publications->length; ++$i) {
				$item = $doc->saveHTML($publications->item($i));
				echo "<div class='plugin_mendeley_item'>$item</div>";
				
			}
		}
		// ==========================================================>>>

		
		
		// BOOKS ____________________________________________________
		// ==========================================================>>>
		if(get_option(publications_books) == "on"){
			
			echo "<div class='underlined_publications'>Books</div>";
			echo "<div class='clearline_publications'></div>";
			$publications = $xpath->query("/html/body//ul[@id='Book']/li/div");
			for($i=0; $i<$publications->length; ++$i) {
				$item = $doc->saveHTML($publications->item($i));
				echo "<div class='plugin_mendeley_item'>$item</div>";
			}
		}
		// ==========================================================>>>

		// CONFERENCE PROCEEDINGS ___________________________________
		// ==========================================================>>>
		if(get_option(publications_conferenceproceedings) == "on"){
			echo "<div class='underlined_publications'>Conference Proceedings</div>";
			echo "<div class='clearline_publications'></div>";
			$publications = $xpath->query("/html/body//ul[@id='ConferenceProceedings']/li/div");
			for($i=0; $i<$publications->length; ++$i) {
				$item = $doc->saveHTML($publications->item($i));
				echo "<div class='plugin_mendeley_item'>$item</div>";
			}
		}
		// ==========================================================>>>
		
		
		// THESIS ___________________________________________________
		// ==========================================================>>>
		if(get_option(publications_thesis) == "on"){
			echo "<div class='underlined_publications'>Thesis</div>";
			echo "<div class='clearline_publications'></div>";
			$publications = $xpath->query("/html/body//ul[@id='Thesis']/li/div");
			for($i=0; $i<$publications->length; ++$i) {
	
				$item = $doc->saveHTML($publications->item($i));
				echo "<div class='plugin_mendeley_item'>$item</div>";
			}
		}
		// ==========================================================>>>

		
		// WEBPAGES _________________________________________________
		// ==========================================================>>>
		if(get_option(publications_webpage) == "on"){
			echo "<div class='underlined_publications'>WebPages</div>";
			echo "<div class='clearline_publications'></div>";
			$publications = $xpath->query("/html/body//ul[@id='WebPage']/li/div");
			for($i=0; $i<$publications->length; ++$i) {
				
				$item = $doc->saveHTML($publications->item($i));
				echo "<div class='plugin_mendeley_item'>$item</div>";
				
			}
		}
		// ==========================================================>>>

		// AWARDS AND GRANTS ________________________________________
		// ==========================================================>>>
		if(get_option(awardsgrants) == "on"){
			echo "<div class='underlined_awards'>Awards and Grants</div>";
			echo "<div class='clearline_awards'></div>";
			$publications = $xpath->query("/html/body//ul[@class='awards_list']/li");
			for($i=0; $i<$publications->length; ++$i) {

				$item = $doc->saveHTML($publications->item($i));
				echo "<div class='plugin_mendeley_item'>$item</div>";
			}
		}
		// ==========================================================>>>
		
		// PROFESSIONAL EXPERIENCE __________________________________
		// ==========================================================>>>
		if(get_option(professional) == "on"){
			echo "<div class='underlined_professional'>Professional Experience</div>";
			echo "<div class='clearline_professional'></div>";
			$publications = $xpath->query("/html/body//div[@id='experiences_info_container']/div");
			for($i=0; $i<$publications->length; ++$i) {
				$item = $doc->saveHTML($publications->item($i));
				echo "<div class='plugin_mendeley_item'>$item</div>";
			}
		}
		// ==========================================================>>>
		
		// EDUCATION ________________________________________________
		// ==========================================================>>>
		if(get_option(education) == "on"){
			echo "<div class='underlined_education'>Education</div>";
			echo "<div class='clearline_education'></div>";
			$publications = $xpath->query("/html/body//div[@id='educations_info_container']/div");
			for($i=0; $i<$publications->length; ++$i) {
				$item = $doc->saveHTML($publications->item($i));
				echo "<div class='plugin_mendeley_item'>$item</div>";
			}
		}
		// ==========================================================>>>

		// POWERED BY _______________________________________________
		// ==========================================================>>>
		if(get_option(workswith) == "on"){
			echo "
			<div class='clearline30'></div>
			<div class='plugin_mendeley_workswith'>
				<div style='float: left;'>
					<img src='http://d3fildg3jlcvty.cloudfront.net/20140513-01/graphics/commonnew/logo-mendeley.png' title='Works with Mendeley' alt='Works with Mendeley'/>
				</div>
				<div class='mendeleypowered'>
					Plugin developed by <a href='http://www.mblazquez.es' title='WP Mendeley Profile Plugin site'>Manuel Blázquez-Ochando</a>
				</div>
			</div>
			<div class='mendeleyline'></div>
			<div class='clearline30'></div>
			";
		}
	} else { echo "Error. Config Mendeley Profile"; }
}
add_shortcode('mendeleyprofile', 'mendeleyProfile');



// =============================================//
// CONFIGURATION                                //
// =============================================//
function mendeleyProfileSettings(){
	if(!current_user_can('manage_options')){ wp_die(__('Sorry. Config your access privileges')); }
	echo "
	<div class='wrap'>
	<h2>Mendeley Profile</h2>
	<form action='options.php' method='post'>
	";
	settings_fields('mendeleyprofile-settings');
	do_settings_sections('mendeleyprofile-settings');

	echo "
	<br/>
	<div><input type='text' size='50' name='urlmendeley' id='urlmendeley' value='"; echo get_option('urlmendeley'); echo "'/> <label for='urlmendeley'>Public Profile URL Mendeley</label></div><br/>
	
	<div><select name='profile' id='profile'><option value='"; echo get_option('profile'); echo "'>"; echo get_option('profile'); echo "</option>
	<option value='on'>on</option><option value='off'>off</option></select> <label for='profile'>Display Mendeley profile - Mostrar perfil de Mendeley</label></div><br/>
	
	<div><select name='profile_photo' id='profile_photo'>
	<option value='"; echo get_option('profile_photo'); echo "'>"; echo get_option('profile_photo'); echo "</option>
	<option value='on'>on</option><option value='off'>off</option></select> <label for='profile_photo'>Display Photo profile - Mostrar fotografía del perfil</label></div><br/>
	
	<div><select name='biograph' id='biograph'>
	<option value='"; echo get_option('biograph'); echo "'>"; echo get_option('biograph'); echo "</option>
	<option value='on'>on</option><option value='off'>off</option></select> <label for='biograph'>Display Biography information - Mostrar información biográfica</label></div><br/>
	
	<div><select name='publications_journalarticle' id='publications_journalarticle'>
	<option value='"; echo get_option('publications_journalarticle'); echo "'>"; echo get_option('publications_journalarticle'); echo "</option>
	<option value='on'>on</option><option value='off'>off</option></select> <label for='publications_journalarticle'>Display journal articles - Mostrar artículos de revista</label></div><br/>
	
	<div><select name='publications_books' id='publications_books'>
	<option value='"; echo get_option('publications_books'); echo "'>"; echo get_option('publications_books'); echo "</option>
	<option value='on'>on</option><option value='off'>off</option></select> <label for='publications_books'>Display books - Mostrar libros</label></div><br/>
	
	<div><select name='publications_conferenceproceedings' id='publications_conferenceproceedings'>
	<option value='"; echo get_option('publications_conferenceproceedings'); echo "'>"; echo get_option('publications_conferenceproceedings'); echo "</option>
	<option value='on'>on</option><option value='off'>off</option></select> <label for='publications_conferenceproceedings'>Display conference proceedings - Mostrar ponencias</label></div><br/>
	
	<div><select name='publications_webpage' id='publications_webpage'>
	<option value='"; echo get_option('publications_webpage'); echo "'>"; echo get_option('publications_webpage'); echo "</option>
	<option value='on'>on</option><option value='off'>off</option></select> <label for='publications_webpage'>Display Webpages - Mostrar páginas web</label></div><br/>
	
	<div><select name='publications_thesis' id='publications_thesis'>
	<option value='"; echo get_option('publications_thesis'); echo "'>"; echo get_option('publications_thesis'); echo "</option>
	<option value='on'>on</option><option value='off'>off</option></select> <label for='publications_thesis'>Display Thesis - Mostrar tesis</label></div><br/>
	
	<div><select name='awardsgrants' id='awardsgrants'>
	<option value='"; echo get_option('awardsgrants'); echo "'>"; echo get_option('awardsgrants'); echo "</option>
	<option value='on'>on</option><option value='off'>off</option></select> <label for='awardsgrants'>Display awards and grants - Mostrar premios</label></div><br/>
	
	<div><select name='professional' id='professional'>
	<option value='"; echo get_option('professional'); echo "'>"; echo get_option('professional'); echo "</option>
	<option value='on'>on</option><option value='off'>off</option></select> <label for='professional'>Display professional experience - Mostrar experiencia profesional</label></div><br/>
	
	<div><select name='education' id='education'>
	<option value='"; echo get_option('education'); echo "'>"; echo get_option('education'); echo "</option>
	<option value='on'>on</option><option value='off'>off</option></select> <label for='education'>Display education - Mostrar formación</label></div><br/>
	
	<div><select name='workswith' id='workswith'>
	<option value='"; echo get_option('workswith'); echo "'>"; echo get_option('workswith'); echo "</option>
	<option value='on'>on</option><option value='off'>off</option></select> <label for='workswith'>Display powered by - Mostrar powered by</label></div>
	";

	submit_button();

	echo "
	</form>
	</div>
	";

}


// =============================================//
// ADD PLUGIN MENU                              //
// =============================================//
function mendeleyProfileMenu() {
	add_options_page( 'Mendeley Profile Settings', 'Mendeley Profile', 'manage_options', 'mendeleyprofile_settings', 'mendeleyProfileSettings' );
}
add_action( 'admin_menu', 'mendeleyProfileMenu' );



// =============================================//
// SAVE SETTINGS                                //
// =============================================//
function mendeleyProfileRegisterSettings(){
	register_setting('mendeleyprofile-settings','urlmendeley');
	register_setting('mendeleyprofile-settings','profile');
	register_setting('mendeleyprofile-settings','profile_photo');
	register_setting('mendeleyprofile-settings','biograph');
	register_setting('mendeleyprofile-settings','publications_journalarticle');
	register_setting('mendeleyprofile-settings','publications_books');
	register_setting('mendeleyprofile-settings','publications_conferenceproceedings');
	register_setting('mendeleyprofile-settings','publications_webpage');
	register_setting('mendeleyprofile-settings','publications_thesis');
	register_setting('mendeleyprofile-settings','awardsgrants');
	register_setting('mendeleyprofile-settings','professional');
	register_setting('mendeleyprofile-settings','education');
	register_setting('mendeleyprofile-settings','workswith');
}
add_action('admin_init','mendeleyProfileRegisterSettings');

?>