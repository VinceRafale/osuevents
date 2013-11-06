<?php
set_time_limit(0);
global  $wpdb;
// COPY THE DUMMY FOLDER ======================================================================
global $upload_folder_path;
global $blog_id;
if(get_option('upload_path') && !strstr(get_option('upload_path'),'wp-content/uploads'))
{
	$upload_folder_path = "wp-content/blogs.dir/$blog_id/files/";
}else
{
	$upload_folder_path = "wp-content/uploads/";
}
global $blog_id;
if($blog_id){ $thumb_url = "&amp;bid=$blog_id";}
$folderpath = $upload_folder_path . "dummy/";
$strpost = strpos(get_stylesheet_directory(),'wp-content');
$dirinfo = wp_upload_dir();
$target =$dirinfo['basedir']."/dummy"; 

full_copy( get_stylesheet_directory()."/images/dummy/", $target );
//full_copy( TEMPLATEPATH."/images/dummy/", ABSPATH . "wp-content/uploads/dummy/" );
function full_copy( $source, $target ) 
{
	global $upload_folder_path;
	$imagepatharr = explode('/',$upload_folder_path."dummy");
	$year_path = ABSPATH;
	for($i=0;$i<count($imagepatharr);$i++)
	{
	  if($imagepatharr[$i])
	  {
		  $year_path .= $imagepatharr[$i]."/";
		  //echo "<br />";
		  if (!file_exists($year_path)){
			  mkdir($year_path, 0777);
		  }     
		}
	}
	@mkdir( $target );
		$d = dir( $source );
		
	if ( is_dir( $source ) ) {
		@mkdir( $target );
		$d = dir( $source );
		while ( FALSE !== ( $entry = $d->read() ) ) {
			if ( $entry == '.' || $entry == '..' ) {
				continue;
			}
			$Entry = $source . '/' . $entry; 
			if ( is_dir( $Entry ) ) {
				full_copy( $Entry, $target . '/' . $entry );
				continue;
			}
			copy( $Entry, $target . '/' . $entry );
		}
	
		$d->close();
	}else {
		copy( $source, $target );
	}
}

require_once(ABSPATH.'wp-admin/includes/taxonomy.php');
$dummy_image_path = get_stylesheet_directory_uri().'/images/dummy/';

/*	Updating General options START	*/

$templatic_settings = get_option('templatic_settings');
$templatic_settings['claim_post_type_value'] = $templatic_settings['claim_post_type_value'];
$templatic_settings['templatic-category_custom_fields'] = 'No';
$templatic_settings['Submit'] = $templatic_settings['Submit'];
$templatic_settings['settings-submit'] = $templatic_settings['settings-submit'];
$templatic_settings['trans_post_type_colour_post'] = $templatic_settings['trans_post_type_colour_post'];
$templatic_settings['trans_post_type_colour_page'] = $templatic_settings['trans_post_type_colour_page'];
$templatic_settings['trans_post_type_colour_event'] = $templatic_settings['trans_post_type_colour_event'];
$templatic_settings['trans_post_type_value'] = $templatic_settings['trans_post_type_value'];
if(in_array('templatic_widgets',$templatic_settings)){
	if($templatic_settings['templatic_widgets']!="" && !empty($templatic_settings['templatic_widgets'])){
		if(!in_array('templatic_aboust_us',$templatic_settings['templatic_widgets'])){
			array_push($templatic_settings['templatic_widgets'],'templatic_aboust_us');
		}
		if(!in_array('templatic_recent_review',$templatic_settings['templatic_widgets'])){
			array_push($templatic_settings['templatic_widgets'],'templatic_recent_review');
		}
		if(!in_array('templatic_social_media',$templatic_settings['templatic_widgets'])){
			array_push($templatic_settings['templatic_widgets'],'templatic_social_media');
		}
		if(!in_array('templatic_slider',$templatic_settings['templatic_widgets'])){
			array_push($templatic_settings['templatic_widgets'],'templatic_slider');
		}
	}else{
		$templatic_settings['templatic_widgets'] = array('templatic_aboust_us','templatic_recent_review','templatic_social_media','templatic_slider');
	}
}else{
	$templatic_settings['templatic_widgets'] = array('templatic_aboust_us','templatic_recent_review','templatic_social_media','templatic_slider');
}
update_option('templatic_settings',$templatic_settings); 
$a = get_option('supreme_theme_settings');
$b = array(
		'supreme_logo_url' 					=> TEMPLATE_CHILD_DIRECTORY_PATH."images/logo.png",
		'supreme_site_description' 			=> $a['supreme_site_description'],
		'supreme_archive_display_excerpt' 	=> $a['supreme_archive_display_excerpt'],
		'supreme_frontpage_display_excerpt' => $a['supreme_frontpage_display_excerpt'],
		'supreme_search_display_excerpt' 	=> $a['supreme_search_display_excerpt'],
		'supreme_header_primary_search' 	=> $a['supreme_header_primary_search'],
		'supreme_header_secondary_search' 	=> $a['supreme_header_secondary_search'],
		'supreme_author_bio_posts' 			=> $a['supreme_author_bio_posts'],
		'supreme_author_bio_pages' 			=> $a['supreme_author_bio_pages'],
		'footer_insert' 					=> '<p class="copyright">Copyright &copy; [the-year] [site-link].</p> <p class="themeby">Designed by <a href="http://templatic.com/" title="Premium wordpress themes"><img src="'.get_stylesheet_directory_uri().'/images/templatic.png"></a></p>',
		'supreme_global_layout' 			=> $a['supreme_global_layout'],
		'supreme_bbpress_layout' 			=> $a['supreme_bbpress_layout'],
		'supreme_buddypress_layout' 		=> $a['supreme_buddypress_layout']
);
update_option('supreme_theme_settings',$b);
update_option("supreme_logo_url",TEMPLATE_CHILD_DIRECTORY_PATH."images/logo.png");
update_option("supreme_theme_settings-supreme_logo_url",TEMPLATE_CHILD_DIRECTORY_PATH."images/logo.png");
update_option("templatic-category_custom_fields","No");
update_option("custom_fields_templates","Active");
update_option("custom_taxonomy","Active");
update_option("templatic-login","Active");

/*	Updating General options END	*/

/* =================================== BLOG SETTING STARTS ====================================== */
//Adding a "Blog" category.
$category_array1 = array('cat_name' => 'Blog', 'category_description' => 'You can write small description here to explain which type of posts are there in this category.');

insert_category($category_array1);
function insert_category($category_array1)
{
	wp_insert_category( $category_array1);
}
/////////////// TERMS END ///////////////

/*Function to insert taxonomy category EOF*/

//Adding some Blogs.
$dummy_image_path = get_template_directory_uri().'/images/dummy/';

$post_array = array();
$blog_image = array();
$post_author = $wpdb->get_var("SELECT ID FROM $wpdb->users order by ID asc limit 1");
$post_info = array();
$blog_image[] = "dummy/blg1.jpg";
$post_info[] = array(
					"post_title"	=>	'An Exhibition',
					"post_content"	=>	"<p>An exhibition, in the most general sense, is an organized presentation and display of a selection of items. In practice, exhibitions usually occur within museums, galleries and exhibition halls, and World's Fairs. Exhibitions include [whatever as in major art museums and small art galleries; interpretive exhibitions, as at natural history museums and history museums], for example; and commercial exhibitions, or trade fairs.</p> <p>The word &quot;exhibition&quot; is usually, but not always, the word used for a collection of items. Sometimes &quot;exhibit&quot; is synonymous with &quot;exhibition&quot;, but &quot;exhibit&quot; generally refers to a single item being exhibited within an exhibition. Exhibitions may be permanent displays or temporary, but in common usage, &quot;exhibitions&quot; are considered temporary and usually scheduled to open and close on specific dates. While many exhibitions are shown in just one venue, some exhibitions are shown in multiple locations and are called travelling exhibitions, and some are online exhibitions.</p> <p>Though exhibitions are common events, the concept of an exhibition is quite wide and encompasses many variables. Exhibitions range from an extraordinarily large event such as a World's Fair exposition to small one-artist solo shows or a display of just one item. Curators are sometimes involved as the people who select the items in an exhibition. Writers and editors are sometimes needed to write text, labels and accompanying printed material such as catalogs and books. Architects, exhibition designers, graphic designers and other designers may be needed to shape the exhibition space and give form to the editorial content. Exhibition also means a scholarship.</p>",
					"post_excerpt" => "<p>An exhibition, in the most general sense, is an organized presentation and display of a selection of items. In practice, exhibitions usually occur within museums, galleries and exhibition halls, and World's Fairs. Exhibitions include [whatever as in major art museums and small art galleries; interpretive exhibitions, as at natural history museums and history museums], for example; and commercial exhibitions, or trade fairs...</p>",
					"post_category"	=>	array('Blog'),
					"post_image"	=>	$blog_image
					);
$blog_image = array();
$blog_image[] = "dummy/blg2.jpg";
$post_info[] = array(
					"post_title"	=>	'Festivals',
					"post_content"	=>	'<p>A festival or gala is an event, usually and ordinarily staged by a local community, which centers on and celebrates some unique aspect of that community and the Festival. Among many religions, a feast is a set of celebrations in honour of God or gods. A feast and a festival are historically interchangeable. However, the term &quot;feast&quot; has also entered common secular parlance as a synonym for any large or elaborate meal. When used as in the meaning of a festival, most often refers to a religious festival rather than a film or art festival. In the Christian liturgical calendar there are two principal feasts, properly known as the Feast of the Nativity of our Lord (Christmas) and the Feast of the Resurrection, (Easter). In the Catholic, Eastern Orthodox, and Anglican liturgical calendars there are a great number of lesser feasts throughout the year commemorating saints, sacred events, doctrines, etc.</p>',
					"post_excerpt" => "<p>A festival or gala is an event, usually and ordinarily staged by a local community, which centers on and celebrates some unique aspect of that community and the Festival. Among many religions, a feast is a set of celebrations in honour of God or gods. A feast and a festival are historically interchangeable. However, the term &quot;feast&quot; has also entered common secular parlance as a synonym for any large or elaborate meal...</p>",
					"post_category"	=>	array('Blog'),
					"post_image"	=>	$blog_image
					);
$blog_image = array();
$blog_image[] = "dummy/blg3.jpg";
$post_info[] = array(
					"post_title"	=>	'Nightlife',
					"post_content"	=>	'Nightlife is the collective term for any entertainment that is available and more popular from the late evening into the early hours of the morning. It includes the public houses, nightclubs, discothèques, bars, live music, concert, cabaret, small theatres, small cinemas, shows, and sometimes restaurants a specific area may have; these venues often require cover charge for admission, and make their money on alcoholic beverages. Nightlife encompasses entertainment from the fairly tame to the risque to the seedy. Nightlife entertainment is inherently edgier than daytime amusements, and usually more oriented to adults, including "adult entertainment" in red-light districts. People who prefer to be active during the night-time are called night owls.',
					"post_excerpt" => "Nightlife is the collective term for any entertainment that is available and more popular from the late evening into the early hours of the morning. It includes the public houses, nightclubs, discothèques, bars, live music, concert, cabaret, small theatres, small cinemas, shows, and sometimes restaurants...",
					"post_category"	=>	array('Blog'),
					"post_image"	=>	$blog_image
					);
$blog_image = array();
$blog_image[] = "dummy/blg4.jpg";
$post_info[] = array(
					"post_title"	=>	'Life Beyond Earth',
					"post_content"	=>	'<p>Extraterrestrial life is defined as life that does not originate from Earth. Referred to as alien life, or simply aliens (or space aliens, to differentiate from other definitions of alien or aliens) these hypothetical forms of life range from simple bacteria-like organisms to beings far more complex than humans. The development and testing of hypotheses on extraterrestrial life is known as exobiology or astrobiology; the term astrobiology, however, includes the study of life on Earth viewed in its astronomical context. Many scientists consider extraterrestrial life to be plausible, but there is no conclusive evidence of the existence of extraterrestrial life.</p>',
					"post_excerpt" => "<p>Extraterrestrial life is defined as life that does not originate from Earth. Referred to as alien life, or simply aliens (or space aliens, to differentiate from other definitions of alien or aliens) these hypothetical forms of life range from simple bacteria-like organisms to beings far more complex than humans...</p>",
					"post_category"	=>	array('Blog'),
					"post_image"	=>	$blog_image
					);
$blog_image = array();
$blog_image[] = "dummy/blg5.jpg";
$post_info[] = array(
					"post_title"	=>	'Social Innovation',
					"post_content"	=>	'<p>Social innovation refers to new strategies, concepts, ideas and organizations that meet social needs of all kinds - from working conditions and education to community development and health - and that extend and strengthen civil society. The term has overlapping meanings. It can be used to refer to social processes of innovation, such as open source methods and techniques. Alternatively it refers to innovations which have a social purpose - like microcredit or distance learning. The concept can also be related to social entrepreneurship (entrepreneurship is not necessarily innovative, but it can be a means of innovation) and it also overlaps with innovation in public policy and governance. Social innovation can take place within government, the for-profit sector, the nonprofit sector (also known as the third sector), or in the spaces between them. Research has focused on the types of platforms needed to facilitate such cross-sector collaborative social innovation. Social innovation is gaining visibility within academia. Prominent innovators associated with the term include Bangladeshi Muhammad Yunus, the founder of Grameen Bank which pioneered the concept of microcredit for supporting innovators in multiple developing countries in Asia, Africa and Latin America and Stephen Goldsmith, former Indianapolis mayor who engaged the private sector in providing many city services.</p>',
					"post_excerpt" => "<p>Social innovation refers to new strategies, concepts, ideas and organizations that meet social needs of all kinds - from working conditions and education to community development and health - and that extend and strengthen civil society. The term has overlapping meanings. It can be used to refer to social processes of innovation, such as open source methods and techniques...</p>",
					"post_category"	=>	array('Blog'),
					"post_image"	=>	$blog_image
					);
					
					
$blog_image = array();
$blog_image[] = "dummy/night1.jpg";
$post_info[] = array(
					"post_title"	=>	'Sample Lorem Ipsum Post',
					"post_content"	=>	'What is Lorem Ipsum?<br /><br />
Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
Why do we use it?<br /><br />It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using &acute;Content here, content here, making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for &acute;lorem ipsum will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
<br /><br />Where does it come from?',
					"post_excerpt" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book...",
					"post_category"	=>	array('Blog'),
					"post_image"	=>	$blog_image,
					);
$blog_image = array();
$blog_image[] = "dummy/night2.jpg";
$post_info[] = array(
					"post_title"	=>	'Sample Blog Post',
					"post_content"	=>	'orem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
					"post_excerpt" => "orem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book...",
					"post_category"	=>	array('Blog'),
					"post_image"	=>	$blog_image,
					);
$blog_image = array();
$blog_image[] = "dummy/night3.jpg";
$post_info[] = array(
					"post_title"	=>	'What is Lorem Ipsum?',
					"post_content"	=>	'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
Why do we use it?<br /><br />It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using &acute;Content here, content here, making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for &acute;lorem ipsum will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
<br /><br />Where does it come from?',
					"post_excerpt" => "It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum...",
					"post_category"	=>	array('Blog'),
					"post_image"	=>	$blog_image,
					);
$blog_image = array();
$blog_image[] = "dummy/night4.jpg";
$post_info[] = array(
					"post_title"	=>	'Letraset sheets',
					"post_content"	=>	'When an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
Why do we use it?<br /><br />It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using &acute;Content here, content here, making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for &acute;lorem ipsum will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).<br /><br />Where does it come from?',
					"post_excerpt" => "When an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum...",	
					"post_category"	=>	array('Blog'),
					"post_image"	=>	$blog_image,
					);
$blog_image = array();
$blog_image[] = "dummy/night5.jpg";
$post_info[] = array(
					"post_title"	=>	'Why do we use it?',
					"post_content"	=>	' It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
Why do we use it?<br /><br />It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using &acute;Content here, content here, making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for &acute;lorem ipsum will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).',
					"post_excerpt" => "It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum...",
					"post_category"	=>	array('Blog'),
					"post_image"	=>	$blog_image,
					);
$blog_image = array();
$blog_image[] = "dummy/night6.jpg";
$post_info[] = array(
					"post_title"	=>	"Celebrating Founder's Day",
					"post_content"	=>	"Founder's Day is commemorated every year on September 13, the day Claude Martin died. Some of the traditions of this day include an extended formal assembly in the morning with a faculty march, a speech by a prominent guest or alumnus, the playing of bagpipes, singing of the school song and other selected hymns by the College choir, and the laying of a wreath at Claude Martin's tomb. For the Founder's Day dinner the entire senior school and staff are treated to an elaborate sit-down dinner in the afternoon. Claude Martin had apparently listed in his will that his death should not be commemorated as a day of mourning but one of celebration of his life. He had also written out a menu for the meal to be served. Although today, the menu does not remain the same, the tradition of the Founder's Day dinner is still preserved. A Founder's Day Social is held in the evening for the senior school. Classes are suspended on Founder's Day, which is generally followed by a school holiday.",
					"post_excerpt" => "Founder's Day is commemorated every year on September 13, the day Claude Martin died. Some of the traditions of this day include an extended formal assembly in the morning with a faculty march, a speech by a prominent guest or alumnus, the playing of bagpipes, singing of the school song and other selected hymns by the College choir, and the laying of a wreath at Claude Martin's tomb...",
					"post_category"	=>	array('News'),
					"post_image"	=>	$blog_image,
					);
$blog_image = array();
$blog_image[] = "dummy/night7.jpg";
$post_info[] = array(
					"post_title"	=>	'Convocation 2012',
					"post_content"	=>	"In some universities, the term 'convocation' refers specifically to the entirety of the alumni of a college which function as one of the university's representative bodies. Due to its inordinate size, the Convocation will elect a standing committee, which is responsible for making representations concerning the views of the alumni to the university administration. The convocation also, however, can hold general meetings, at which any alumnus can attend. The main function of the convocation is to represent the views of the alumni to the university administration, to encourage co-operation among alumni (esp. in regard to donations), and to elect members of the University's governing body (known variously as the Senate, Council, Board, etc., depending on the particular institution, but basically equivalent to a board of directors of a corporation.). In the University of Oxford, Convocation was originally the main governing body of the University, consisting of all doctors and masters of the University, but it now comprises all graduates of the university and its only remaining function is to elect the Chancellor of the University and the Professor of Poetry.",
					"post_excerpt" => "In some universities, the term 'convocation' refers specifically to the entirety of the alumni of a college which function as one of the university's representative bodies. Due to its inordinate size, the Convocation will elect a standing committee, which is responsible for making representations concerning the views of the alumni to the university administration...",
					"post_category"	=>	array('Blog'),
					"post_image"	=>	$blog_image,
					);


					
/***- Insert Blog post BOF-***/
insert_posts($post_info);
require_once(ABSPATH . 'wp-admin/includes/image.php');
function insert_posts($post_info)
{
	global $wpdb,$current_user;
	for($i=0;$i<count($post_info);$i++)
	{
		$post_title = $post_info[$i]['post_title'];
		$post_count = $wpdb->get_var("SELECT count(ID) FROM $wpdb->posts where post_title like \"$post_title\" and post_type='post' and post_status in ('publish','draft')");
		if(!$post_count)
		{
			$post_info_arr = array();
			$catids_arr = array();
			$my_post = array();
			$post_info_arr = $post_info[$i];
			if($post_info_arr['post_category'])
			{
				for($c=0;$c<count($post_info_arr['post_category']);$c++)
				{
					$catids_arr[] = get_cat_ID($post_info_arr['post_category'][$c]);
				}
			}else
			{
				$catids_arr[] = 1;
			}
			$my_post['post_title'] = $post_info_arr['post_title'];
			$my_post['post_content'] = $post_info_arr['post_content'];
			if(@$post_info_arr['post_author'])
			{
				$my_post['post_author'] = $post_info_arr['post_author'];
			}else
			{
				$my_post['post_author'] = 1;
			}
			$my_post['post_status'] = 'publish';
			$my_post['post_category'] = $catids_arr;
			@$my_post['tags_input'] = $post_info_arr['post_tags'];
			$last_postid = wp_insert_post( $my_post );
			add_post_meta($last_postid,'auto_install', "auto_install");
			$post_meta = @$post_info_arr['post_meta'];
			if($post_meta)
			{
				foreach($post_meta as $mkey=>$mval)
				{
					update_post_meta($last_postid, $mkey, $mval);
				}
			}
			
			$post_image = @$post_info_arr['post_image'];
			if($post_image)
			{
				for($m=0;$m<count($post_image);$m++)
				{
					$menu_order = $m+1;
					$image_name_arr = explode('/',$post_image[$m]);
					$img_name = $image_name_arr[count($image_name_arr)-1];
					$img_name_arr = explode('.',$img_name);
					$post_img = array();
					$post_img['post_title'] = $img_name_arr[0];
					$post_img['post_status'] = 'attachment';
					$post_img['post_parent'] = $last_postid;
					$post_img['post_type'] = 'attachment';
					$post_img['post_mime_type'] = 'image/jpeg';
					$post_img['menu_order'] = $menu_order;
					$last_postimage_id = wp_insert_post( $post_img );
					update_post_meta($last_postimage_id, '_wp_attached_file', $post_image[$m]);					
					$post_attach_arr = array(
										"width"	=>	570,
										"height" =>	400,
										"hwstring_small"=> "height='180' width='140'",
										"file"	=> $post_image[$m],
										//"sizes"=> $sizes_info_array,
										);
					wp_update_attachment_metadata($last_postimage_id, $post_attach_arr );
				}
			}
		}
	}
}
/***- Insert Blog post EOF-***/

/* ========================================== EVENTS SETTING STARTS ================================== */
//Add some categories in "EVENT" post type.
$category_array1 = array();
$category_array1 = array('Exhibitions','Kids','Festivals','Nightlife','Social');
insert_taxonomy_category($category_array1);
/*--Function to insert taxonomy category BOF-*/
function insert_taxonomy_category($category_array1)
{
	global $wpdb;
	for($i=0;$i<count($category_array1);$i++)
	{
		$parent_catid = 0;
		if(is_array($category_array1[$i]))
		{
			$cat_name_arr = $category_array1[$i];
			for($j=0;$j<count($cat_name_arr);$j++)
			{
				$catname = $cat_name_arr[$j];
				if($j>1)
				{
					$catid = $wpdb->get_var("select term_id from $wpdb->terms where name=\"$catname\"");
					if(!$catid)
					{
						$last_catid = wp_insert_term( $catname, 'ecategory' );
					}					
				}else
				{
					$catid = $wpdb->get_var("select term_id from $wpdb->terms where name=\"$catname\"");
					if(!$catid)
					{
						$last_catid = wp_insert_term( $catname, 'ecategory');
					}
				}
			}
			
		}else
		{
			$catname = $category_array1[$i];
			$catid = $wpdb->get_var("select term_id from $wpdb->terms where name=\"$catname\"");
			if(!$catid)
			{
				wp_insert_term( $catname, 'ecategory');
			}
		}
	}
	
	for($i=0;$i<count($category_array1);$i++)
	{
		$parent_catid = 0;
		if(is_array($category_array1[$i]))
		{
			$cat_name_arr = $category_array1[$i];
			for($j=0;$j<count($cat_name_arr);$j++)
			{
				$catname = $cat_name_arr[$j];
				if($j>0)
				{
					$parentcatname = $cat_name_arr[0];
					$parent_catid = $wpdb->get_var("select term_id from $wpdb->terms where name=\"$parentcatname\"");
					$last_catid = $wpdb->get_var("select term_id from $wpdb->terms where name=\"$catname\"");
					wp_update_term( $last_catid, 'ecategory', $args = array('parent'=>$parent_catid) );
				}
			}
			
		}
	}
}

//===================== Add some Events ======================//
$post_info = array();
$today = date_i18n(get_option('date_format'),strtotime(date('Y-m-d')));
////Event 1 start///
$image_array = array();
$post_meta = array();
$post_comments = array();
$post_comments [0] = array("just tell her and see what happens, but at some point your gonna have to anyways",'scott@gmail.com');
$post_comments [1] = array("ust make it sooner rather than later, and show her the ring to",'rajesh@gmail.com');
$tags_input = array('Festival','Nightlife','location','night events');
$image_array[] = "dummy/a1.jpg";
$image_array[] = "dummy/a2.jpg";
$image_array[] = "dummy/a3.jpg";
$date = date_i18n(get_option('date_format'), strtotime("+1 month"));
$post_meta = array(
					"address"			=> 'Carolina Beach Road, Wilmington, NC, United States',	
					"geo_latitude"		=> '34.1334600363166',		
					"geo_longitude"		=> '-77.91745350000002',		
					"map_view"			=> 'Road Map',		
					"st_date"			=> $today, //Full Time,Part Time,freelance
					"st_time"			=> '10.00',	
					"end_date"			=> $date,
					"end_time"			=> '05.00',
					"reg_desc"			=> '',
					"event_type"		=> 'Regular event',
					"phone"				=> '+91123456789',
					"email"				=> 'mymail@gmail.com',
					"website"			=> 'http://mysite.com',
					"twitter"			=> 'http://twitter.com/myplace',
					"facebook"			=> 'http://facebook.com/myplace',
					"video"				=> '', 
					"organizer_name"	=> 'Castor Event Organizers', 
					"organizer_email"	=> 'steve@event.com', 
					"organizer_logo"	=> '', 
					"organizer_address"	=> '5 Buckingham Dr Street, paris, NX, USA - 21478', 
					"organizer_contact"	=> '01-025-98745871', 
					"organizer_website"	=> 'http://steve.com', 
					"organizer_mobile"	=> '0897456123071', 
					"organizer_desc"	=> "<strong>What we do?</strong><br/><p>An event is normally a large gathering of people, who have come to a particular place at a particular time for a particular reason. Having said that, there's very little that's normal about an event. In our experience, each one is different and their variety is enormous. And that's as it should be: an event is something special. Aone - off. We plan these occasions in meticulous details, manage them from the ground, dismantle them when they are over and assess the result.</p>",
					"featured_type"		=> 'both',
					"featured_h"		=> 'h',
					"featured_c"		=> 'c',
					"alive_days"		=> '30',
					"tl_dummy_content"		=> '1',
					"show_on_detail"		=> '1'
				);
$post_info[] = array(
					"post_title"	=>	'An Art Exhibition',
					"post_content"	=>	"<p>Discover how war shapes lives at Imperial War Museum London. Explore six floors of galleries and displays, including a permanent exhibition dedicated to the Holocaust and a changing programme of special temporary exhibitions.</p><p> Chronicling the history of conflict from the First World War to the present day, the Museum's vast Collections range from tanks and aircraft to photographs and personal letters as well as films, sound recordings and some of the twentieth century's best-known paintings. With a daily programme of family activities, film screenings, special talks and lectures, the Museum offers a variety of events. </p><br/>FREE (NB: special exhibitions may charge an admission fee) ",
					"post_excerpt" => "<p>Discover how war shapes lives at Imperial War Museum London. Explore six floors of galleries and displays, including a permanent exhibition dedicated to the Holocaust and a changing programme of special temporary exhibitions...</p>",
					"post_comments"	=>	$post_comments,
					"post_meta"		=>	$post_meta,
					"post_image"	=>	$image_array,
					"post_feature"	=>	0,
					"tags_input"	=>	$tags_input,
					"post_category"	=>	array('Exhibitions'),
					);
////Event 1 end///
////Event 2 start///
$image_array = array();
$post_meta = array();
$post_comments = array();
$post_comments [0] = array("just tell her and see what happens, but at some point your gonna have to anyways",'scott@gmail.com');
$post_comments [1] = array("ust make it sooner rather than later, and show her the ring to",'rajesh@gmail.com');
$tags_input = array('Festival','Nightlife');
$image_array[] = "dummy/k1.jpg";
$image_array[] = "dummy/k6.jpg";
$image_array[] = "dummy/k7.jpg";
$date = date_i18n(get_option('date_format'), strtotime("+3 days"));
$date1 = date_i18n(get_option('date_format'), strtotime("+1 month"));
$post_meta = array(
					"address"			=> 'Dakota Street, Winnipeg, MB, Canada',	
					"geo_latitude"		=> '49.82057499773663',		
					"geo_longitude"		=> '-97.10196274999998',
					"map_view"			=> 'Road Map',		
					"st_date"			=>  $date, //Full Time,Part Time,freelance
					"st_time"			=> '10.00',	
					"end_date"			=> $date1,
					"end_time"			=> '05.00',
					"reg_desc"			=> '',
					"event_type"		=> 'Recurring event',
					"recurrence_occurs"	=> 'weekly',
					"recurrence_per"	=> '2',
					"recurrence_bydays"	=> '2',
					"recurrence_days"	=> 3,
					"phone"				=> '+91123456789',
					"email"				=> 'mymail@gmail.com',
					"website"			=> 'http://mysite.com',
					"twitter"			=> 'http://twitter.com/myplace',
					"facebook"			=> 'http://facebook.com/myplace',
					"video"				=> '', 
					"organizer_name"	=> 'Castor Event Organizers', 
					"organizer_email"	=> 'steve@event.com', 
					"organizer_logo"	=> '', 
					"organizer_address"	=> '5 Buckingham Dr Street, paris, NX, USA - 21478', 
					"organizer_contact"	=> '01-025-98745871', 
					"organizer_website"	=> 'http://steve.com', 
					"organizer_mobile"	=> '0897456123071', 
					"organizer_desc"	=> "<strong>What we do?</strong><br/><p>An event is normally a large gathering of people, who have come to a particular place at a particular time for a particular reason. Having said that, there's very little that's normal about an event. In our experience, each one is different and their variety is enormous. And that's as it should be: an event is something special. Aone - off. We plan these occasions in meticulous details, manage them from the ground, dismantle them when they are over and assess the result.</p>",
					"featured_type"		=> 'both',
					"featured_h"		=> 'h',
					"featured_c"		=> 'c',
					"alive_days"		=> '30',
					"tl_dummy_content"		=> '1',
					"show_on_detail"		=> '1'
				);
$post_info[] = array(
					"post_title"	=>	'Weekly Karate Classes',
					"post_content"	=>	'Tate Modern is the national gallery of international modern art. Located in London, it is one of the family of four Tate galleries which display selections from the Tate Collection. The Collection comprises the national collection of British art from the year 1500 to the present day, and of international modern art.<br/>
					Sunday – Thursday, 10.00–18.00<br/>
					Friday and Saturday, 10.00–22.00<br/>
					Last admission into exhibitions 17.15 (Friday and Saturday 21.15)<br/>
					Closed 24, 25 and 26 December (open as normal on 1 January).',
					"post_excerpt" => "Tate Modern is the national gallery of international modern art. Located in London, it is one of the family of four Tate galleries which display selections from the Tate Collection. The Collection comprises the national collection of British art from the year 1500 to the present day, and of international modern art...",
					"post_comments"	=>	$post_comments,
					"post_meta"		=>	$post_meta,
					"post_image"	=>	$image_array,
					"tags_input"	=>	$tags_input,
					"post_feature"	=>	0,
					"post_category"	=>	array('Kids'),
					);
////Event 2 end///
////Event 3 start///
$image_array = array();
$post_meta = array();
$post_comments = array();
$post_comments [0] = array("just tell her and see what happens, but at some point your gonna have to anyways",'scott@gmail.com');
$post_comments [1] = array("ust make it sooner rather than later, and show her the ring to",'rajesh@gmail.com');
$tags_input = array('Festival','Nightlife','location');
$image_array[] = "dummy/ch1.jpg";
$image_array[] = "dummy/ch2.jpg";
$image_array[] = "dummy/ch5.jpg";
$date = date_i18n(get_option('date_format'), strtotime("+1 month"));
$post_meta = array(
					"address"			=> 'Alaskan Way, Seattle, WA, United State',	
					"geo_latitude"		=> '47.59064284101658',		
					"geo_longitude"		=> '-122.33772579999999',		
					"map_view"			=> 'Road Map',		
					"st_date"			=> $today, //Full Time,Part Time,freelance
					"st_time"			=> '10.00',	
					"end_date"			=> $date,
					"end_time"			=> '05.00',
					"reg_desc"			=> '',
					"event_type"		=> 'Regular event',
					"phone"				=> '+91123456789',
					"email"				=> 'mymail@gmail.com',
					"website"			=> 'http://mysite.com',
					"twitter"			=> 'http://twitter.com/myplace',
					"facebook"			=> 'http://facebook.com/myplace',
					"video"				=> '', 
					"organizer_name"	=> 'Castor Event Organizers', 
					"organizer_email"	=> 'steve@event.com', 
					"organizer_logo"	=> '', 
					"organizer_address"	=> '5 Buckingham Dr Street, paris, NX, USA - 21478', 
					"organizer_contact"	=> '01-025-98745871', 
					"organizer_website"	=> 'http://steve.com', 
					"organizer_mobile"	=> '0897456123071', 
					"organizer_desc"	=> "<strong>What we do?</strong><br/><p>An event is normally a large gathering of people, who have come to a particular place at a particular time for a particular reason. Having said that, there's very little that's normal about an event. In our experience, each one is different and their variety is enormous. And that's as it should be: an event is something special. Aone - off. We plan these occasions in meticulous details, manage them from the ground, dismantle them when they are over and assess the result.</p><br/> <strong>How we do it?</strong><br/> <p>Events can be used to communicate key message, faster community relations, motivate work forces or raise funds. One of the first things we ask our clients is, what they want to achieve from their event. This is the cornerstone of the whole operation for us, our starting point and most importantly, it's the way success can be measured.</p>",
					"featured_type"		=> 'h',
					"featured_h"		=> 'h',
					"featured_c"		=> 'n',
					"alive_days"		=> '30',
					"tl_dummy_content"		=> '1',
					"show_on_detail"		=> '1'
					);
$post_info[] = array(
					"post_title"	=>	'Christmas Carnival',
					"post_content"	=>	"This year the Real Food Festival will be showcasing some of the finest small producers in the country on London's Southbank in September. You can see some pictures from last years Real Food Festival by clicking the link below - from hand made chocolates to cheeses, from chutneys to sausages and olive oils, it was a time to celebrate everything that's good about British food.",
					"post_excerpt" => "This year the Real Food Festival will be showcasing some of the finest small producers in the country on London's Southbank in September.",
					"post_comments"	=>	$post_comments,
					"post_meta"		=>	$post_meta,
					"post_image"	=>	$image_array,
					"post_feature"	=>	0,
					"tags_input"	=>	$tags_input,
					"post_category"	=>	array('Festivals'),
					);
////Event 3 end///
////Event 4 start///
$image_array = array();
$post_meta = array();
$post_comments = array();
$post_comments [0] = array("just tell her and see what happens, but at some point your gonna have to anyways",'scott@gmail.com');
$post_comments [1] = array("ust make it sooner rather than later, and show her the ring to",'rajesh@gmail.com');
$tags_input = array('Festival','Nightlife','location','night events');
$image_array[] = "dummy/cs1.jpg";
$image_array[] = "dummy/cs3.jpg";
$image_array[] = "dummy/cs5.jpg";
$date = date_i18n(get_option('date_format'), strtotime("-3 days"));
$date1 = date_i18n(get_option('date_format'), strtotime("+1 month"));
$post_meta = array(
					"address"			=> 'California Avenue Southwest, Seattle, WA, United States',	
					"geo_latitude"		=> '47.550281221089804',		
					"geo_longitude"		=> '-122.38634315000002',		
					"map_view"			=> 'Road Map',		
					"st_date"			=> $date, //Full Time,Part Time,freelance
					"st_time"			=> '10.00',	
					"end_date"			=> $date1,
					"end_time"			=> '05.00',
					"reg_desc"			=> '',
					"event_type"		=> 'Regular event',
					"phone"				=> '+91123456789',
					"email"				=> 'mymail@gmail.com',
					"website"			=> 'http://mysite.com',
					"twitter"			=> 'http://twitter.com/myplace',
					"facebook"			=> 'http://facebook.com/myplace',
					"video"				=> '', 
					"organizer_name"	=> 'Castor Event Organizers', 
					"organizer_email"	=> 'steve@event.com', 
					"organizer_logo"	=> '', 
					"organizer_address"	=> '5 Buckingham Dr Street, paris, NX, USA - 21478', 
					"organizer_contact"	=> '01-025-98745871', 
					"organizer_website"	=> 'http://steve.com', 
					"organizer_mobile"	=> '0897456123071', 
					"organizer_desc"	=> "<strong>What we do?</strong><br/><p>An event is normally a large gathering of people, who have come to a particular place at a particular time for a particular reason. Having said that, there's very little that's normal about an event. In our experience, each one is different and their variety is enormous. And that's as it should be: an event is something special. Aone - off. We plan these occasions in meticulous details, manage them from the ground, dismantle them when they are over and assess the result.</p><br/> <strong>How we do it?</strong><br/> <p>Events can be used to communicate key message, faster community relations, motivate work forces or raise funds. One of the first things we ask our clients is, what they want to achieve from their event. This is the cornerstone of the whole operation for us, our starting point and most importantly, it's the way success can be measured.</p>",
					"featured_type"		=> 'h',
					"featured_h"		=> 'h',
					"featured_c"		=> 'n',
					"alive_days"		=> '30',
					"tl_dummy_content"		=> '1',
					"show_on_detail"		=> '1'
					);
$post_info[] = array(
					"post_title"	=>	'The Royal Casinos',
					"post_content"	=>	"Major Mondays is the Ku bars super sexy student night in the Ku Klub. Hosted by DJ P (Wigout, Lloyds hit factory) and guests, it will be a fun filled night of cheap drinks, hot boys and the best commercial Pop, R&B, Indie and Old School tracks – Plus your requests – Request a tune on face book and pick up your free shot when it’s played on the night<br/>
					10pm – 3am <br/> Facebook group - major Mondays",
					"post_excerpt" => "Major Mondays is the Ku bars super sexy student night in the Ku Klub. Hosted by DJ P (Wigout, Lloyds hit factory) and guests, it will be a fun filled night of cheap drinks, hot boys and the best commercial Pop...",
					"post_comments"	=>	$post_comments,
					"post_meta"		=>	$post_meta,
					"post_image"	=>	$image_array,
					"post_feature"	=>	0,
					"tags_input"	=>	$tags_input,
					"post_category"	=>	array('Nightlife'),
					);
////Event 4 end///
////Event 5 start///
$image_array = array();
$post_meta = array();
$post_comments = array();
$post_comments [0] = array("just tell her and see what happens, but at some point your gonna have to anyways",'scott@gmail.com');
$post_comments [1] = array("ust make it sooner rather than later, and show her the ring to",'rajesh@gmail.com');
$tags_input = array('Festival');
$image_array[] = "dummy/bi1.jpg";
$image_array[] = "dummy/bi3.jpg";
$image_array[] = "dummy/bi4.jpg";
$date = date_i18n(get_option('date_format'), strtotime("-5 days"));
$date1 = date_i18n(get_option('date_format'), strtotime("+1 month"));
$post_meta = array(
					"address"			=> 'Illinois Street, San Francisco, CA, United States',	
					"geo_latitude"		=> '37.756374007080936',		
					"geo_longitude"		=> '-122.38552264999998',		
					"map_view"			=> 'Road Map',		
					"st_date"			=> $date, //Full Time,Part Time,freelance
					"st_time"			=> '10.00',	
					"end_date"			=> $date1,
					"end_time"			=> '05.00',
					"reg_desc"			=> '',
					"event_type"		=> 'Regular event',
					"phone"				=> '+91123456789',
					"email"				=> 'mymail@gmail.com',
					"website"			=> 'http://mysite.com',
					"twitter"			=> 'http://twitter.com/myplace',
					"facebook"			=> 'http://facebook.com/myplace',
					"video"				=> '', 
					"organizer_name"	=> 'Castor Event Organizers', 
					"organizer_email"	=> 'steve@event.com', 
					"organizer_logo"	=> '', 
					"organizer_address"	=> '5 Buckingham Dr Street, paris, NX, USA - 21478', 
					"organizer_contact"	=> '01-025-98745871', 
					"organizer_website"	=> 'http://steve.com', 
					"organizer_mobile"	=> '0897456123071', 
					"organizer_desc"	=> "<strong>What we do?</strong><br/><p>An event is normally a large gathering of people, who have come to a particular place at a particular time for a particular reason. Having said that, there's very little that's normal about an event. In our experience, each one is different and their variety is enormous. And that's as it should be: an event is something special. Aone - off. We plan these occasions in meticulous details, manage them from the ground, dismantle them when they are over and assess the result.</p><br/> <strong>How we do it?</strong><br/> <p>Events can be used to communicate key message, faster community relations, motivate work forces or raise funds. One of the first things we ask our clients is, what they want to achieve from their event. This is the cornerstone of the whole operation for us, our starting point and most importantly, it's the way success can be measured.</p>",
					"featured_type"		=> 'c',
					"featured_h"		=> 'n',
					"featured_c"		=> 'c',
					"alive_days"		=> '30',
					"tl_dummy_content"		=> '1',
					"show_on_detail"		=> '1'
					);
$post_info[] = array(
					"post_title"	=>	'Birthday Party on the Rocks',
					"post_content"	=>	"<p>Join other people all around the country in finding fun, imaginative ways to promote the goal of a Zero Carbon Britain by 2030.</p> <p>Find ways to convey to people that its both urgently necessary and feasible. We can really do it if the whole of society pulls together and starts implementing the solutions that are already out there. And there will be many added benefits too !</p> <p>There are all sorts of things that you can do - preferably teaming together with as many other people in your area as possible. A public meeting to discuss the solutions, a stall and/or display in a public place, a picnic, a cycle ride, a parade, street theatre.</p>",
					"post_excerpt"		=>	"<p>Join other people all around the country in finding fun, imaginative ways to promote the goal of a Zero Carbon Britain by 2030.</p> <p>Find ways to convey to people that its both urgently necessary and feasible...</p>",
					"post_comments"	=>	$post_comments,
					"post_meta"		=>	$post_meta,
					"post_image"	=>	$image_array,
					"post_feature"	=>	0,
					"tags_input"	=>	$tags_input,
					"post_category"	=>	array('Social'),
					);
////Event 5 end///
////Event 6 start///
$image_array = array();
$post_meta = array();
$post_comments = array();
$post_comments [0] = array("just tell her and see what happens, but at some point your gonna have to anyways",'scott@gmail.com');
$post_comments [1] = array("ust make it sooner rather than later, and show her the ring to",'rajesh@gmail.com');
$tags_input = array('Festival','Nightlife','location');
$image_array[] = "dummy/p3.jpg";
$image_array[] = "dummy/p4.jpg";
$image_array[] = "dummy/p5.jpg";
$date = date_i18n(get_option('date_format'), strtotime("-5 days"));
$date1 = date_i18n(get_option('date_format'), strtotime("+1 month"));
$post_meta = array(
					"address"			=> 'Indiana Street, San Francisco, CA, United States',	
					"geo_latitude"		=> '37.756085590154804',		
					"geo_longitude"		=> '-122.39106915000002',		
					"map_view"			=> 'Road Map',		
					"st_date"			=> $date, //Full Time,Part Time,freelance
					"st_time"			=> '10.00',	
					"end_date"			=> $date1,
					"end_time"			=> '05.00',
					"reg_desc"			=> '',
					"event_type"		=> 'Recurring event',
					"recurrence_occurs"	=> 'weekly',
					"recurrence_per"	=> '3',
					"recurrence_bydays"	=> '6',
					"recurrence_days"	=> 1,
					"phone"				=> '+91123456789',
					"email"				=> 'mymail@gmail.com',
					"website"			=> 'http://mysite.com',
					"twitter"			=> 'http://twitter.com/myplace',
					"facebook"			=> 'http://facebook.com/myplace',
					"video"				=> '', 
					"organizer_name"	=> 'Castor Event Organizers', 
					"organizer_email"	=> 'steve@event.com', 
					"organizer_logo"	=> '', 
					"organizer_address"	=> '5 Buckingham Dr Street, paris, NX, USA - 21478', 
					"organizer_contact"	=> '01-025-98745871', 
					"organizer_website"	=> 'http://steve.com', 
					"organizer_mobile"	=> '0897456123071', 
					"organizer_desc"	=> "<strong>What we do?</strong><br/><p>An event is normally a large gathering of people, who have come to a particular place at a particular time for a particular reason. Having said that, there's very little that's normal about an event. In our experience, each one is different and their variety is enormous. And that's as it should be: an event is something special. Aone - off. We plan these occasions in meticulous details, manage them from the ground, dismantle them when they are over and assess the result.</p><br/> <strong>How we do it?</strong><br/> <p>Events can be used to communicate key message, faster community relations, motivate work forces or raise funds. One of the first things we ask our clients is, what they want to achieve from their event. This is the cornerstone of the whole operation for us, our starting point and most importantly, it's the way success can be measured.</p>",
					"featured_type"		=> 'c',
					"featured_h"		=> 'n',
					"featured_c"		=> 'c',
					"alive_days"		=> '30',
					"tl_dummy_content"		=> '1',
					"show_on_detail"		=> '1'
					);
$post_info[] = array(
					"post_title"	=>	'Painting Exhibition',
					"post_content"	=>	"<p>Discover how war shapes lives at Imperial War Museum London. Explore six floors of galleries and displays, including a permanent exhibition dedicated to the Holocaust and a changing programme of special temporary exhibitions.</p><p> Chronicling the history of conflict from the First World War to the present day, the Museum's vast Collections range from tanks and aircraft to photographs and personal letters as well as films, sound recordings and some of the twentieth century's best-known paintings. With a daily programme of family activities, film screenings, special talks and lectures, the Museum offers a variety of events. </p><br/>FREE (NB: special exhibitions may charge an admission fee) ",
					"post_excerpt"		=>	"<p>Discover how war shapes lives at Imperial War Museum London. Explore six floors of galleries and displays, including a permanent exhibition dedicated to the Holocaust and a changing programme of special temporary exhibitions...</p>",
					"post_comments"	=>	$post_comments,
					"post_meta"		=>	$post_meta,
					"post_image"	=>	$image_array,
					"post_feature"	=>	0,
					"tags_input"	=>	$tags_input,
					"post_category" =>	array('Exhibitions'),
					);
////Event 6 end///
////Event 7 start///
$image_array = array();
$post_meta = array();
$post_comments = array();
$post_comments [0] = array("just tell her and see what happens, but at some point your gonna have to anyways",'scott@gmail.com');
$post_comments [1] = array("ust make it sooner rather than later, and show her the ring to",'rajesh@gmail.com');
$tags_input = array('Festival','Nightlife','location','night events');
$image_array[] = "dummy/d1.jpg";
$image_array[] = "dummy/d2.jpg";
$image_array[] = "dummy/d3.jpg";
$date = date_i18n(get_option('date_format'), strtotime("+5 days"));
$date1 = date_i18n(get_option('date_format'), strtotime("+1 month"));
$post_meta = array(
					"address"			=> 'Kansas City, KS, United States',	
					"geo_latitude"		=> '39.114052993477756',		
					"geo_longitude"		=> '-94.6274636',		
					"map_view"			=> 'Road Map',		
					"st_date"			=> $date, //Full Time,Part Time,freelance
					"st_time"			=> '10.00',	
					"end_date"			=> $date1,
					"end_time"			=> '05.00',
					"reg_desc"			=> '',
					"event_type"		=> 'Regular event',
					"phone"				=> '+91123456789',
					"email"				=> 'mymail@gmail.com',
					"website"			=> 'http://mysite.com',
					"twitter"			=> 'http://twitter.com/myplace',
					"facebook"			=> 'http://facebook.com/myplace',
					"video"				=> '', 
					"organizer_name"	=> 'Castor Event Organizers', 
					"organizer_email"	=> 'steve@event.com', 
					"organizer_logo"	=> '', 
					"organizer_address"	=> '5 Buckingham Dr Street, paris, NX, USA - 21478', 
					"organizer_contact"	=> '01-025-98745871', 
					"organizer_website"	=> 'http://steve.com', 
					"organizer_mobile"	=> '0897456123071', 
					"organizer_desc"	=> "<strong>What we do?</strong><br/><p>An event is normally a large gathering of people, who have come to a particular place at a particular time for a particular reason. Having said that, there's very little that's normal about an event. In our experience, each one is different and their variety is enormous. And that's as it should be: an event is something special. Aone - off. We plan these occasions in meticulous details, manage them from the ground, dismantle them when they are over and assess the result.</p><br/> <strong>How we do it?</strong><br/> <p>Events can be used to communicate key message, faster community relations, motivate work forces or raise funds. One of the first things we ask our clients is, what they want to achieve from their event. This is the cornerstone of the whole operation for us, our starting point and most importantly, it's the way success can be measured.</p>",
					"featured_type"		=> 'none',
					"featured_h"		=> 'n',
					"featured_c"		=> 'n',
					"alive_days"		=> '30',
					"tl_dummy_content"		=> '1',
					"show_on_detail"		=> '1'
					);
$post_info[] = array(
					"post_title"	=>	'Summer Dance Week',
					"post_content"	=>	"Tate Modern is the national gallery of international modern art. Located in London, it is one of the family of four Tate galleries which display selections from the Tate Collection. The Collection comprises the national collection of British art from the year 1500 to the present day, and of international modern art.<br/>
					Sunday – Thursday, 10.00–18.00<br/>
					Friday and Saturday, 10.00–22.00<br/>
					Last admission into exhibitions 17.15 (Friday and Saturday 21.15)<br/>
					Closed 24, 25 and 26 December (open as normal on 1 January).",
					"post_excerpt"		=>	"Tate Modern is the national gallery of international modern art. Located in London, it is one of the family of four Tate galleries which display selections from the Tate Collection...",
					"post_comments"	=>	$post_comments,
					"post_meta"		=>	$post_meta,
					"post_image"	=>	$image_array,
					"post_feature"	=>	0,
					"tags_input"	=>	$tags_input,
					"post_category" =>	array('Kids'),
					);
////Event 7 end///
////Event 8 start///
$image_array = array();
$post_meta = array();
$post_comments = array();
$post_comments [0] = array("just tell her and see what happens, but at some point your gonna have to anyways",'scott@gmail.com');
$post_comments [1] = array("ust make it sooner rather than later, and show her the ring to",'rajesh@gmail.com');
$tags_input = array();
$image_array[] = "dummy/t3.jpg";
$image_array[] = "dummy/t4.jpg";
$image_array[] = "dummy/t5.jpg";
$date = Date(get_option('date_format'), strtotime("+1 month"));
$post_meta = array(
					"address"			=> 'Kentucky Street, Lawrence, KS, United States',	
					"geo_latitude"		=> '38.95892457569876',		
					"geo_longitude"		=> '-95.23837049999997',		
					"map_view"			=> 'Road Map',		
					"st_date"			=> $today, //Full Time,Part Time,freelance
					"st_time"			=> '10.00',	
					"end_date"			=> $date,
					"end_time"			=> '05.00',
					"reg_desc"			=> '',
					"phone"				=> '+91123456789',
					"email"				=> 'mymail@gmail.com',
					"website"			=> 'http://mysite.com',
					"twitter"			=> 'http://twitter.com/myplace',
					"facebook"			=> 'http://facebook.com/myplace',
					"video"				=> '',
					"event_type"		=> 'Regular event',
					"organizer_name"	=> 'Castor Event Organizers', 
					"organizer_email"	=> 'steve@event.com', 
					"organizer_logo"	=> '', 
					"organizer_address"	=> '5 Buckingham Dr Street, paris, NX, USA - 21478', 
					"organizer_contact"	=> '01-025-98745871', 
					"organizer_website"	=> 'http://steve.com', 
					"organizer_mobile"	=> '0897456123071', 
					"organizer_desc"	=> "<strong>What we do?</strong><br /><p>An event is normally a large gathering of people, who have come to a particular place at a particular time for a particular reason. Having said that, there's very little that's normal about an event. In our experience, each one is different and their variety is enormous. And that's as it should be: an event is something special. Aone - off. We plan these occasions in meticulous details, manage them from the ground, dismantle them when they are over and assess the result.</p><br/> <strong>How we do it?</strong><br /> <p>Events can be used to communicate key message, faster community relations, motivate work forces or raise funds. One of the first things we ask our clients is, what they want to achieve from their event. This is the cornerstone of the whole operation for us, our starting point and most importantly, it's the way success can be measured.</p>",
					"featured_type"		=> 'none',
					"featured_h"		=> 'n',
					"featured_c"		=> 'n',
					"alive_days"		=> '30',
					"tl_dummy_content"		=> '1',
					"show_on_detail"		=> '1'
					);
$post_info[] = array(
					"post_title"	=>	'La Tomatina',
					"post_content"	=>	"This year the Real Food Festival will be showcasing some of the finest small producers in the country on London's Southbank in September. You can see some pictures from last years Real Food Festival by clicking the link below - from hand made chocolates to cheeses, from chutneys to sausages and olive oils, it was a time to celebrate everything that's good about British food.",
					"post_excerpt"		=>	"This year the Real Food Festival will be showcasing some of the finest small producers in the country on London's Southbank in September. You can see some pictures from last years...",
					"post_comments"	=>	$post_comments,
					"post_meta"		=>	$post_meta,
					"post_image"	=>	$image_array,
					"post_feature"	=>	0,
					"tags_input"	=>	$tags_input,
					"post_category" =>	array('Festivals'),
					);
////Event 8 end///
////Event 9 start///
$image_array = array();
$post_meta = array();
$post_comments = array();
$post_comments [0] = array("just tell her and see what happens, but at some point your gonna have to anyways",'scott@gmail.com');
$post_comments [1] = array("ust make it sooner rather than later, and show her the ring to",'rajesh@gmail.com');
$tags_input = array('Festival','Nightlife','location','night events');
$image_array[] = "dummy/f3.jpg";
$image_array[] = "dummy/f4.jpg";
$image_array[] = "dummy/f5.jpg";
$date = Date(get_option('date_format'), strtotime("+2 days"));
$date1 = Date(get_option('date_format'), strtotime("+1 month"));
$post_meta = array(
					"address"			=> 'Louisiana Street, Houston, TX, United States',	
					"geo_latitude"		=> '29.75549595189908',		
					"geo_longitude"		=> '-95.37178675000001',		
					"map_view"			=> 'Road Map',		
					"st_date"			=> $date, //Full Time,Part Time,freelance
					"st_time"			=> '10.00',	
					"end_date"			=> $date1,
					"end_time"			=> '05.00',
					"reg_desc"			=> '',
					"event_type"		=> 'Regular event',
					"phone"				=> '+91123456789',
					"email"				=> 'mymail@gmail.com',
					"website"			=> 'http://mysite.com',
					"twitter"			=> 'http://twitter.com/myplace',
					"facebook"			=> 'http://facebook.com/myplace',
					"video"				=> '', 
					"organizer_name"	=> 'Castor Event Organizers', 
					"organizer_email"	=> 'steve@event.com', 
					"organizer_logo"	=> '', 
					"organizer_address"	=> '5 Buckingham Dr Street, paris, NX, USA - 21478', 
					"organizer_contact"	=> '01-025-98745871', 
					"organizer_website"	=> 'http://steve.com', 
					"organizer_mobile"	=> '0897456123071', 
					"organizer_desc"	=> "<strong>What we do?</strong><br /><p>An event is normally a large gathering of people, who have come to a particular place at a particular time for a particular reason. Having said that, there's very little that's normal about an event. In our experience, each one is different and their variety is enormous. And that's as it should be: an event is something special. Aone - off. We plan these occasions in meticulous details, manage them from the ground, dismantle them when they are over and assess the result.</p><br /> <strong>How we do it?</strong><br /> <p>Events can be used to communicate key message, faster community relations, motivate work forces or raise funds. One of the first things we ask our clients is, what they want to achieve from their event. This is the cornerstone of the whole operation for us, our starting point and most importantly, it's the way success can be measured.</p>",
					"featured_type"		=> 'none',
					"featured_h"		=> 'n',
					"featured_c"		=> 'n',
					"alive_days"		=> '30',
					"tl_dummy_content"		=> '1',
					"show_on_detail"		=> '1'
					);
$post_info[] = array(
					"post_title"	=>	'Food Streets at Night',
					"post_content"	=>	"Major Mondays is the Ku bars super sexy student night in the Ku Klub. Hosted by DJ P (Wigout, Lloyds hit factory) and guests, it will be a fun filled night of cheap drinks, hot boys and the best commercial Pop, R&B, Indie and Old School tracks – Plus your requests – Request a tune on face book and pick up your free shot when it’s played on the night<br/>
					10pm – 3am <br /> Facebook group - major Mondays",
					"post_excerpt"		=>	"Major Mondays is the Ku bars super sexy student night in the Ku Klub. Hosted by DJ P (Wigout, Lloyds hit factory) and guests, it will be a fun filled night of cheap drinks, hot boys and the best commercial Pop, R&B...",
					"post_comments"	=>	$post_comments,
					"post_meta"		=>	$post_meta,
					"post_image"	=>	$image_array,
					"post_feature"	=>	0,
					"tags_input"	=>	$tags_input,
					"post_category" =>	array('Nightlife'),
					);
////Event 9 end///
////Event 10 start///
$image_array = array();
$post_meta = array();
$post_comments = array();
$post_comments [0] = array("just tell her and see what happens, but at some point your gonna have to anyways",'scott@gmail.com');
$post_comments [1] = array("ust make it sooner rather than later, and show her the ring to",'rajesh@gmail.com');
$tags_input = array('Festival','Nightlife','location','night events');
$image_array[] = "dummy/m1.jpg";
$image_array[] = "dummy/m2.jpg";
$image_array[] = "dummy/m3.jpg";
$date = Date(get_option('date_format'), strtotime("-5 days"));
$date1 = Date(get_option('date_format'), strtotime("+1 month"));
$post_meta = array(
					"address"			=> 'Massachusetts Avenue Northwest, Washington, DC, United States',	
					"geo_latitude"		=> '38.92256631958141',		
					"geo_longitude"		=> '-77.05360435',		
					"map_view"			=> 'Road Map',		
					"st_date"			=> $date, //Full Time,Part Time,freelance
					"st_time"			=> '10.00',	
					"end_date"			=> $date1,
					"end_time"			=> '05.00',
					"reg_desc"			=> '',
					"phone"				=> '+91123456789',
					"email"				=> 'mymail@gmail.com',
					"website"			=> 'http://mysite.com',
					"twitter"			=> 'http://twitter.com/myplace',
					"facebook"			=> 'http://facebook.com/myplace',
					"video"				=> '',
					"event_type"		=> 'Recurring event',
					"recurrence_occurs"	=> 'monthly',
					"recurrence_per"	=> '2',
					"recurrence_bydays"	=> '2',
					"recurrence_days"	=> 3,
					"organizer_name"	=> 'Castor Event Organizers', 
					"organizer_email"	=> 'steve@event.com', 
					"organizer_logo"	=> '', 
					"organizer_address"	=> '5 Buckingham Dr Street, paris, NX, USA - 21478', 
					"organizer_contact"	=> '01-025-98745871', 
					"organizer_website"	=> 'http://steve.com', 
					"organizer_mobile"	=> '0897456123071', 
					"organizer_desc"	=> "<strong>What we do?</strong><br /><p>An event is normally a large gathering of people, who have come to a particular place at a particular time for a particular reason. Having said that, there's very little that's normal about an event. In our experience, each one is different and their variety is enormous. And that's as it should be: an event is something special. Aone - off. We plan these occasions in meticulous details, manage them from the ground, dismantle them when they are over and assess the result.</p><br /> <strong>How we do it?</strong><br /> <p>Events can be used to communicate key message, faster community relations, motivate work forces or raise funds. One of the first things we ask our clients is, what they want to achieve from their event. This is the cornerstone of the whole operation for us, our starting point and most importantly, it's the way success can be measured.</p>",
					"featured_type"		=> 'none',
					"featured_h"		=> 'n',
					"featured_c"		=> 'n',
					"alive_days"		=> '30',
					"tl_dummy_content"		=> '1',
					"show_on_detail"		=> '1'
					);
$post_info[] = array(
					"post_title"	=>	'Community Meeting',
					"post_content"	=>	"<p>Join other people all around the country in finding fun, imaginative ways to promote the goal of a Zero Carbon Britain by 2030.</p> <p>Find ways to convey to people that its both urgently necessary and feasible. We can really do it if the whole of society pulls together and starts implementing the solutions that are already out there. And there will be many added benefits too !</p> <p>There are all sorts of things that you can do - preferably teaming together with as many other people in your area as possible. A public meeting to discuss the solutions, a stall and/or display in a public place, a picnic, a cycle ride, a parade, street theatre.</p>",
					"post_excerpt"		=>	"<p>Join other people all around the country in finding fun, imaginative ways to promote the goal of a Zero Carbon Britain by 2030.</p> <p>Find ways to convey to people that its both urgently necessary and feasible...</p>",
					"post_comments"	=>	$post_comments,
					"post_meta"		=>	$post_meta,
					"post_image"	=>	$image_array,
					"post_feature"	=>	0,
					"tags_input"	=>	$tags_input,
					"post_category" =>	array('Social'),
					);
////Event 10 end///
////Event 11 start///
$image_array = array();
$post_meta = array();
$post_comments = array();
$post_comments [0] = array("just tell her and see what happens, but at some point your gonna have to anyways",'scott@gmail.com');
$post_comments [1] = array("ust make it sooner rather than later, and show her the ring to",'rajesh@gmail.com');
$tags_input = array('Festival');
$image_array[] = "dummy/c4.jpg";
$image_array[] = "dummy/c5.jpg";
$image_array[] = "dummy/c6.jpg";
$date = Date(get_option('date_format'), strtotime("+5 days"));
$date1 = Date(get_option('date_format'), strtotime("+1 month"));
$post_meta = array(
					"address"			=> 'Maryland Avenue, Rockville, MD, United States',	
					"geo_latitude"		=> '39.081568368325996',		
					"geo_longitude"		=> '-77.15622340000004',		
					"map_view"			=> 'Road Map',		
					"st_date"			=> $date, //Full Time,Part Time,freelance
					"st_time"			=> '10.00',	
					"end_date"			=> $date1,
					"end_time"			=> '05.00',
					"reg_desc"			=> '',
					"phone"				=> '+91123456789',
					"email"				=> 'mymail@gmail.com',
					"website"			=> 'http://mysite.com',
					"twitter"			=> 'http://twitter.com/myplace',
					"facebook"			=> 'http://facebook.com/myplace',
					"video"				=> '',
					"event_type"		=> 'Regular event',
					"organizer_name"	=> 'Castor Event Organizers', 
					"organizer_email"	=> 'steve@event.com', 
					"organizer_logo"	=> '', 
					"organizer_address"	=> '5 Buckingham Dr Street, paris, NX, USA - 21478', 
					"organizer_contact"	=> '01-025-98745871', 
					"organizer_website"	=> 'http://steve.com', 
					"organizer_mobile"	=> '0897456123071', 
					"organizer_desc"	=> "<strong>What we do?</strong><br /><p>An event is normally a large gathering of people, who have come to a particular place at a particular time for a particular reason. Having said that, there's very little that's normal about an event. In our experience, each one is different and their variety is enormous. And that's as it should be: an event is something special. Aone - off. We plan these occasions in meticulous details, manage them from the ground, dismantle them when they are over and assess the result.</p><br /> <strong>How we do it?</strong><br /> <p>Events can be used to communicate key message, faster community relations, motivate work forces or raise funds. One of the first things we ask our clients is, what they want to achieve from their event. This is the cornerstone of the whole operation for us, our starting point and most importantly, it's the way success can be measured.</p>",
					"featured_type"		=> 'none',
					"featured_h"		=> 'n',
					"featured_c"		=> 'n',
					"alive_days"		=> '30',
					"tl_dummy_content"		=> '1',
					"show_on_detail"		=> '1'
					);
$post_info[] = array(
					"post_title"	=>	'Old Cars Exhibition',
					"post_content"	=>	"<p>Discover how war shapes lives at Imperial War Museum London. Explore six floors of galleries and displays, including a permanent exhibition dedicated to the Holocaust and a changing programme of special temporary exhibitions.</p><p> Chronicling the history of conflict from the First World War to the present day, the Museum's vast Collections range from tanks and aircraft to photographs and personal letters as well as films, sound recordings and some of the twentieth century's best-known paintings. With a daily programme of family activities, film screenings, special talks and lectures, the Museum offers a variety of events. </p><br />FREE (NB: special exhibitions may charge an admission fee) ",
					"post_excerpt"		=>	"<p>Discover how war shapes lives at Imperial War Museum London. Explore six floors of galleries and displays, including a permanent exhibition dedicated to the Holocaust and a changing programme of special temporary exhibitions...</p>",
					"post_comments"	=>	$post_comments,
					"post_meta"		=>	$post_meta,
					"post_image"	=>	$image_array,
					"post_feature"	=>	0,
					"tags_input"	=>	$tags_input,
					"post_category" =>	array('Exhibitions'),
					);
////Event 11 end///
////Event 12 start///
$image_array = array();
$post_meta = array();
$post_comments = array();
$post_comments [0] = array("This is Test Comment,just tell her and see what happens, but at some point your gonna have to anyways",'scott@gmail.com');
$post_comments [1] = array("This is Test Comment,ust make it sooner rather than later, and show her the ring to",'rajesh@gmail.com');
$tags_input = array('Festival','Nightlife','location','night events');
$image_array[] = "dummy/h3.jpg";
$image_array[] = "dummy/h4.jpg";
$image_array[] = "dummy/h6.jpg";
$date1 = Date(get_option('date_format'), strtotime("+1 month"));
$post_meta = array(
					"address"			=> 'Maine Avenue Southwest, Washington, DC, United States',	
					"geo_latitude"		=> '38.88207077465083',		
					"geo_longitude"		=> '-77.02876980000002',		
					"map_view"			=> 'Road Map',		
					"st_date"			=> $today, //Full Time,Part Time,freelance
					"st_time"			=> '10.00',	
					"end_date"			=> $date1,
					"end_time"			=> '05.00',
					"reg_desc"			=> '',
					"phone"				=> '+91123456789',
					"email"				=> 'mymail@gmail.com',
					"website"			=> 'http://mysite.com',
					"twitter"			=> 'http://twitter.com/myplace',
					"facebook"			=> 'http://facebook.com/myplace',
					"video"				=> '',
					"event_type"		=> 'Regular event',					
					"organizer_name"	=> 'Castor Event Organizers', 
					"organizer_email"	=> 'steve@event.com', 
					"organizer_logo"	=> '', 
					"organizer_address"	=> '5 Buckingham Dr Street, paris, NX, USA - 21478', 
					"organizer_contact"	=> '01-025-98745871', 
					"organizer_website"	=> 'http://steve.com', 
					"organizer_mobile"	=> '0897456123071', 
					"organizer_desc"	=> "<strong>What we do?</strong><br /><p>An event is normally a large gathering of people, who have come to a particular place at a particular time for a particular reason. Having said that, there's very little that's normal about an event. In our experience, each one is different and their variety is enormous. And that's as it should be: an event is something special. Aone - off. We plan these occasions in meticulous details, manage them from the ground, dismantle them when they are over and assess the result.</p><br /> <strong>How we do it?</strong><br /> <p>Events can be used to communicate key message, faster community relations, motivate work forces or raise funds. One of the first things we ask our clients is, what they want to achieve from their event. This is the cornerstone of the whole operation for us, our starting point and most importantly, it's the way success can be measured.</p>",
					"featured_type"		=> 'none',
					"featured_h"		=> 'n',
					"featured_c"		=> 'n',
					"alive_days"		=> '30',
					"tl_dummy_content"		=> '1',
					"show_on_detail"		=> '1'
					);
$post_info[] = array(
					"post_title"	=>	'Colorful Holi',
					"post_content"	=>	"This year the Real Food Festival will be showcasing some of the finest small producers in the country on London's Southbank in September. You can see some pictures from last years Real Food Festival by clicking the link below - from hand made chocolates to cheeses, from chutneys to sausages and olive oils, it was a time to celebrate everything that's good about British food.",
					"post_excerpt"		=>	"This year the Real Food Festival will be showcasing some of the finest small producers in the country on London's Southbank in September. You can see some pictures from last years...",
					"post_comments"	=>	$post_comments,
					"post_meta"		=>	$post_meta,
					"post_image"	=>	$image_array,
					"post_feature"	=>	0,
					"tags_input"	=>	$tags_input,
					"post_category" =>	array('Festivals'),
					);
////Event 12 end///
////Event 13 start///
$image_array = array();
$post_meta = array();
$post_comments = array();
$post_comments [0] = array("This is Test Comment from author, just tell her and see what happens, but at some point your gonna have to anyways",'scott@gmail.com');
$post_comments [1] = array("This is Test Comment from author, ust make it sooner rather than later, and show her the ring to",'rajesh@gmail.com');
$tags_input = array('Festival','Nightlife','location');
$image_array[] = "dummy/b2.jpg";
$image_array[] = "dummy/b4.jpg";
$image_array[] = "dummy/b5.jpg";
$date = Date(get_option('date_format'), strtotime("+7 days"));
$date1 = Date(get_option('date_format'), strtotime("+1 month"));
$post_meta = array(
					"address"			=> 'New Hampshire Avenue, Hillandale, MD, United States',	
					"geo_latitude"		=> '39.02404183584668',		
					"geo_longitude"		=> '-76.97746959999995',		
					"map_view"			=> 'Road Map',		
					"st_date"			=> $date, //Full Time,Part Time,freelance
					"st_time"			=> '10.00',	
					"end_date"			=> $date1,
					"end_time"			=> '05.00',
					"reg_desc"			=> '',
					"phone"				=> '+91123456789',
					"email"				=> 'mymail@gmail.com',
					"website"			=> 'http://mysite.com',
					"twitter"			=> 'http://twitter.com/myplace',
					"facebook"			=> 'http://facebook.com/myplace',
					"video"				=> '',
					"event_type"		=> 'Regular event',					
					"organizer_name"	=> 'Castor Event Organizers', 
					"organizer_email"	=> 'steve@event.com', 
					"organizer_logo"	=> '', 
					"organizer_address"	=> '5 Buckingham Dr Street, paris, NX, USA - 21478', 
					"organizer_contact"	=> '01-025-98745871', 
					"organizer_website"	=> 'http://steve.com', 
					"organizer_mobile"	=> '0897456123071', 
					"organizer_desc"	=> "<strong>What we do?</strong><br /><p>An event is normally a large gathering of people, who have come to a particular place at a particular time for a particular reason. Having said that, there's very little that's normal about an event. In our experience, each one is different and their variety is enormous. And that's as it should be: an event is something special. Aone - off. We plan these occasions in meticulous details, manage them from the ground, dismantle them when they are over and assess the result.</p><br /> <strong>How we do it?</strong><br /> <p>Events can be used to communicate key message, faster community relations, motivate work forces or raise funds. One of the first things we ask our clients is, what they want to achieve from their event. This is the cornerstone of the whole operation for us, our starting point and most importantly, it's the way success can be measured.</p>",
					"featured_type"		=> 'none',
					"featured_h"		=> 'n',
					"featured_c"		=> 'n',
					"alive_days"		=> '30',
					"tl_dummy_content"		=> '1',
					"show_on_detail"		=> '1'
					);
$post_info[] = array(
					"post_title"	=>	'Baseball Champs',
					"post_content"	=>	'Tate Modern is the national gallery of international modern art. Located in London, it is one of the family of four Tate galleries which display selections from the Tate Collection. The Collection comprises the national collection of British art from the year 1500 to the present day, and of international modern art.<br/>
					Sunday – Thursday, 10.00–18.00<br/>
					Friday and Saturday, 10.00–22.00<br/>
					Last admission into exhibitions 17.15 (Friday and Saturday 21.15)<br/>
					Closed 24, 25 and 26 December (open as normal on 1 January).',
					"post_excerpt"		=>	"Tate Modern is the national gallery of international modern art. Located in London, it is one of the family of four Tate galleries which display selections from the Tate Collection...",
					"post_comments"	=>	$post_comments,
					"post_meta"		=>	$post_meta,
					"post_image"	=>	$image_array,
					"post_feature"	=>	0,
					"tags_input"	=>	$tags_input,
					"company_logo"		=> $dummy_image_path.'logo3.png',
					"post_category" =>	array('Kids'),
					);
////Event 13 end///
////Event 14 start///
$image_array = array();
$post_meta = array();
$post_comments = array();
$post_comments [0] = array("just tell her and see what happens, but at some point your gonna have to anyways",'scott@gmail.com');
$post_comments [1] = array("ust make it sooner rather than later, and show her the ring to",'rajesh@gmail.com');
$tags_input = array('Festival','Nightlife','location','night events');
$image_array[] = "dummy/di1.jpg";
$image_array[] = "dummy/di2.jpg";
$image_array[] = "dummy/di4.jpg";
$date = Date(get_option('date_format'), strtotime("-7 days"));
$date1 = Date(get_option('date_format'), strtotime("+1 month"));
$post_meta = array(
					"address"			=> 'New Jersey Turnpike, Mount Laurel, NJ, United States',	
					"geo_latitude"		=> '39.95796638154377',		
					"geo_longitude"		=> '-74.91579899999999',		
					"map_view"			=> 'Road Map',		
					"st_date"			=> $date, //Full Time,Part Time,freelance
					"st_time"			=> '10.00',	
					"end_date"			=> $date1,
					"end_time"			=> '05.00',
					"reg_desc"			=> '',
					"phone"				=> '+91123456789',
					"email"				=> 'mymail@gmail.com',
					"website"			=> 'http://mysite.com',
					"twitter"			=> 'http://twitter.com/myplace',
					"facebook"			=> 'http://facebook.com/myplace',
					"video"				=> '',
					"event_type"		=> 'Regular event',
					"organizer_name"	=> 'Castor Event Organizers', 
					"organizer_email"	=> 'steve@event.com', 
					"organizer_logo"	=> '', 
					"organizer_address"	=> '5 Buckingham Dr Street, paris, NX, USA - 21478', 
					"organizer_contact"	=> '01-025-98745871', 
					"organizer_website"	=> 'http://steve.com', 
					"organizer_mobile"	=> '0897456123071', 
					"organizer_desc"	=> "<strong>What we do?</strong><br /><p>An event is normally a large gathering of people, who have come to a particular place at a particular time for a particular reason. Having said that, there's very little that's normal about an event. In our experience, each one is different and their variety is enormous. And that's as it should be: an event is something special. Aone - off. We plan these occasions in meticulous details, manage them from the ground, dismantle them when they are over and assess the result.</p><br /> <strong>How we do it?</strong><br /> <p>Events can be used to communicate key message, faster community relations, motivate work forces or raise funds. One of the first things we ask our clients is, what they want to achieve from their event. This is the cornerstone of the whole operation for us, our starting point and most importantly, it's the way success can be measured.</p>",
					"featured_type"		=> 'none',
					"featured_h"		=> 'n',
					"featured_c"		=> 'n',
					"alive_days"		=> '30',
					"tl_dummy_content"		=> '1',
					"show_on_detail"		=> '1'
					);
$post_info[] = array(
					"post_title"	=>	'The Dance Floor',
					"post_content"	=>	"Major Mondays is the Ku bars super sexy student night in the Ku Klub. Hosted by DJ P (Wigout, Lloyds hit factory) and guests, it will be a fun filled night of cheap drinks, hot boys and the best commercial Pop, R&B, Indie and Old School tracks – Plus your requests – Request a tune on face book and pick up your free shot when it’s played on the night<br />
					10pm – 3am </br> Facebook group - major Mondays",
					"post_excerpt"		=>	"Major Mondays is the Ku bars super sexy student night in the Ku Klub. Hosted by DJ P (Wigout, Lloyds hit factory) and guests, it will be a fun filled night of cheap drinks, hot boys...",
					"post_comments"	=>	$post_comments,
					"post_meta"		=>	$post_meta,
					"post_image"	=>	$image_array,
					"post_feature"	=>	0,
					"tags_input"	=>	$tags_input,
					"post_category" =>	array('Nightlife'),
					);
////Event 14 end///
////Event 15 start///
$image_array = array();
$post_meta = array();
$post_comments = array();
$post_comments [0] = array("This is Test Comment,just tell her and see what happens, but at some point your gonna have to anyways",'scott@gmail.com');
$post_comments [1] = array("This is Test Comment,ust make it sooner rather than later, and show her the ring to",'rajesh@gmail.com');
$tags_input = array('Festival','Nightlife','location');
$image_array[] = "dummy/w1.jpg";
$image_array[] = "dummy/w2.jpg";
$image_array[] = "dummy/w3.jpg";
$date = Date('Y-m-d', strtotime("-5 days"));
$date1 = Date('Y-m-d', strtotime("+1 month"));
$post_meta = array(
					"address"			=> 'New Mexico 15, Silver City, NM, United States',	
					"geo_latitude"		=> '47.550281221089804',		
					"geo_longitude"		=> '-122.38634315000002',		
					"map_view"			=> 'Road Map',		
					"st_date"			=> $date, //Full Time,Part Time,freelance
					"st_time"			=> '10.00',	
					"end_date"			=> $date1,
					"end_time"			=> '05.00',
					"reg_desc"			=> '',
					"phone"				=> '+91123456789',
					"email"				=> 'mymail@gmail.com',
					"website"			=> 'http://mysite.com',
					"twitter"			=> 'http://twitter.com/myplace',
					"facebook"			=> 'http://facebook.com/myplace',
					"video"				=> '',
					"event_type"		=> 'Regular event',
					"organizer_name"	=> 'Castor Event Organizers', 
					"organizer_email"	=> 'steve@event.com', 
					"organizer_logo"	=> '', 
					"organizer_address"	=> '5 Buckingham Dr Street, paris, NX, USA - 21478', 
					"organizer_contact"	=> '01-025-98745871', 
					"organizer_website"	=> 'http://steve.com', 
					"organizer_mobile"	=> '0897456123071', 
					"organizer_desc"	=> "<strong>What we do?</strong><br /><p>An event is normally a large gathering of people, who have come to a particular place at a particular time for a particular reason. Having said that, there's very little that's normal about an event. In our experience, each one is different and their variety is enormous. And that's as it should be: an event is something special. Aone - off. We plan these occasions in meticulous details, manage them from the ground, dismantle them when they are over and assess the result.</p><br /> <strong>How we do it?</strong><br /> <p>Events can be used to communicate key message, faster community relations, motivate work forces or raise funds. One of the first things we ask our clients is, what they want to achieve from their event. This is the cornerstone of the whole operation for us, our starting point and most importantly, it's the way success can be measured.</p>",
					"featured_type"		=> 'none',
					"featured_h"		=> 'n',
					"featured_c"		=> 'n',
					"alive_days"		=> '30',
					"tl_dummy_content"		=> '1',
					"show_on_detail"		=> '1'
					);
$post_info[] = array(
					"post_title"	=>	'The Wedding',
					"post_content"	=>	"<p>Join other people all around the country in finding fun, imaginative ways to promote the goal of a Zero Carbon Britain by 2030.</p> <p>Find ways to convey to people that its both urgently necessary and feasible. We can really do it if the whole of society pulls together and starts implementing the solutions that are already out there. And there will be many added benefits too !</p> <p>There are all sorts of things that you can do - preferably teaming together with as many other people in your area as possible. A public meeting to discuss the solutions, a stall and/or display in a public place, a picnic, a cycle ride, a parade, street theatre.</p>",
					"post_excerpt"		=>	"<p>Join other people all around the country in finding fun, imaginative ways to promote the goal of a Zero Carbon Britain by 2030.</p> <p>Find ways to convey to people that its both urgently necessary and feasible...</p>",
					"post_comments"	=>	$post_comments,
					"post_meta"		=>	$post_meta,
					"post_image"	=>	$image_array,
					"post_feature"	=>	0,
					"tags_input"	=>	$tags_input,
					"post_category" =>	array('Social'),
					);
////Event 15 end///

insert_taxonomy_posts($post_info);
function insert_taxonomy_posts($post_info)
{
	global $wpdb,$current_user;
	for($i=0;$i<count($post_info);$i++)
	{
		$post_title = $post_info[$i]['post_title'];
		$post_count = $wpdb->get_var("SELECT count(ID) FROM $wpdb->posts where post_title like \"$post_title\" and post_type='event' and post_status in ('publish','draft')");
		if(!$post_count)
		{
			$post_info_arr = array();
			$catids_arr = array();
			$my_post = array();
			$post_info_arr = $post_info[$i];
			$my_post['post_title'] = $post_info_arr['post_title'];
			$my_post['post_content'] = $post_info_arr['post_content'];
			$my_post['post_type'] = "event";
			if(@$post_info_arr['post_author'])
			{
				$my_post['post_author'] = $post_info_arr['post_author'];
			}else
			{
				$my_post['post_author'] = 1;
			}
			$my_post['post_status'] = 'publish';
			$my_post['post_category'] = $post_info_arr['post_category'];
			$last_postid = wp_insert_post( $my_post );
			add_post_meta($last_postid,'auto_install', "auto_install");
			wp_set_object_terms($last_postid,$post_info_arr['post_category'], $taxonomy = 'ecategory');
			wp_set_post_terms($last_postid,$post_info_arr['tags_input'],'etags');
			$post_meta = $post_info_arr['post_meta'];
			if($post_meta)
			{
				foreach($post_meta as $mkey=>$mval)
				{
					update_post_meta($last_postid, $mkey, $mval);
				}
			}
			if($post_meta)
			{
				foreach($post_meta as $mkey=>$mval)
				{
					if(trim(strtolower($mval)) == trim(strtolower('Recurring event')))
					{
						$start_date = templ_recurrence_dates($last_postid);
						update_post_meta($last_postid,'recurring_search_date',$start_date);
					}
				}
			}
			
			$post_image1 = $post_info_arr['post_image'];
			if($post_image1)
			{
				for($m=0;$m<count($post_image1);$m++)
				{
					$menu_order1 = $m+1;
					$image_name_arr1 = explode('/',$post_image1[$m]);
					$img_name1 = $image_name_arr1[count($image_name_arr1)-1];
					$img_name_arr1 = explode('.',$img_name1);
					$post_img1 = array();
					$post_img1['post_title'] = $img_name_arr1[0];
					$post_img1['post_status'] = 'inherit';
					$post_img1['post_parent'] = $last_postid;
					$post_img1['post_type'] = 'attachment';
					$post_img1['post_mime_type'] = 'image/jpeg';
					$post_img1['menu_order'] = $menu_order1;
					$last_postimage_id2 = wp_insert_post( $post_img1 );
					update_post_meta($last_postimage_id2, '_wp_attached_file', $post_image1[$m]);				
					$post_attach_arr1 = array(
										"width"				=>	570,
										"height" 			=>	400,
										"hwstring_small"	=> array("file"=>$post_image1[$m],"height"=>125 ,"width"=>75),
										"post-thumbnails"	=> array("file"=>$post_image1[$m],"height"=>125 ,"width"=>75),
										"detail_page_image"	=>  array("file"=>$post_image1[$m],"height"=>400, "width"=>570),
										"file"				=> $post_image1[$m],
										//"sizes"=> $sizes_info_array,
										);	
					wp_update_attachment_metadata( $last_postimage_id2, $post_attach_arr1 );
				}
			}
			
			$post_comments1 = array();
			$post_comments1  = $post_info_arr['post_comments'];
			$comment_ratting_table_name = $wpdb->prefix.'ratings';
			if($post_comments1)
			{
				for($comm=0;$comm<count($post_comments1);$comm++)
				{
					$commentinfo = 	$post_comments1[$comm];
					$author = $commentinfo[1];
					$userinfo_str = $wpdb->get_results("select ID,user_email,display_name from $wpdb->users where user_login='admin'");
					foreach($userinfo_str as $userinfo_strobj)
					{
						$comment_author_email = 	$userinfo_strobj->user_email;
						$user_ID = 	$userinfo_strobj->ID;
						$display_name = 	$userinfo_strobj->display_name;
					}
					$comment_post_ID = $last_postid;
					$comment_author_url = '';
					$comment_content = $commentinfo[0];
					$comment_type = '';
					$comment_parent = 0;					
					$comment_date = date('Y-m-d H:i:s');
					$comment_rating_val = 3;
					$comment_rating_ip = getenv("REMOTE_ADDR");
					$wpdb->query("insert into $wpdb->comments (comment_post_ID,comment_author,comment_author_email,comment_author_IP,comment_date,comment_date_gmt,comment_content ,comment_approved,user_id) values ($comment_post_ID,\"$display_name\",\"$comment_author_email\",'',\"$comment_date\",\"$comment_date\",\"$comment_content\",1,\"$user_ID\")");
					$last_comment_id = mysql_insert_id();
					$commsount = $wpdb->get_var("select count(comment_ID) from $wpdb->comments where comment_post_ID=\"$comment_post_ID\"");
					$wpdb->query("update $wpdb->posts set comment_count=\"$commsount\" where ID=\"$comment_post_ID\"");
					$wpdb->query("INSERT INTO $comment_ratting_table_name (rating_postid,rating_rating,comment_id,rating_ip,rating_userid) VALUES ( \"$comment_post_ID\", \"$comment_rating_val\",\"$last_comment_id\",\"$comment_rating_ip\",\"$user_ID \")");
				}
				
			}
		}
	}
}

// ADD EVENT TAGS
function set_post_tag($pid,$post_tags)
{
	global $wpdb;
	$post_tags_arr = explode(',',$post_tags);
	for($t=0;$t<count($post_tags_arr);$t++)
	{
		$posttag = $post_tags_arr[$t];
		$term_id = $wpdb->get_var("SELECT t.term_id FROM $wpdb->terms t join $wpdb->term_taxonomy tt on tt.term_id=t.term_id where t.name=\"$posttag\" and tt.taxonomy='post_tag'");
		if($term_id == '')
		{
			$srch_arr = array('&amp;',"'",'"',"?",".","!","@","#","$","%","^","&","*","(",")","-","+","+"," ",';',',','_');
				$replace_arr = array('','','','','','','','','','','','','','','','','','','','',',','','');
			$posttagslug = str_replace($srch_arr,$replace_arr,$posttag);
			$termsql = "insert into $wpdb->terms (name,slug) values (\"$posttag\",\"$posttagslug\")";
			$wpdb->query($termsql);
			$last_termsid = $wpdb->get_var("SELECT max(term_id) as term_id FROM $wpdb->terms");
		}else
		{
			$last_termsid = $term_id;
		}
		$term_taxonomy_id = $wpdb->get_var("SELECT term_taxonomy_id FROM $wpdb->term_taxonomy where term_id=\"$last_termsid\" and taxonomy='post_tag'");
		if($term_taxonomy_id=='')
		{
			$termpost = "insert into $wpdb->term_taxonomy (term_id,taxonomy,count) values (\"$last_termsid\",'post_tag',1)";
			$wpdb->query($termpost);
			$term_taxonomy_id = $wpdb->get_var("SELECT term_taxonomy_id FROM $wpdb->term_taxonomy where term_id=\"$last_termsid\" and taxonomy='post_tag'");
		}else
		{
			$termpost = "update $wpdb->term_taxonomy set count=count+1 where term_taxonomy_id=\"$term_taxonomy_id\"";
			$wpdb->query($termpost);
		}
		$termsql = "insert into $wpdb->term_relationships (object_id,term_taxonomy_id) values (\"$pid\",\"$term_taxonomy_id\")";
		$wpdb->query($termsql);
	}
}

/* ========================================= ADDING PAGE TEMPLATES =========================================== */
$pages_array = array();
$pages_array = array('About Us','Submit event','User Attending Event',array('Page Templates', 'Advanced Search', 'Contact Us', 'Archives', 'Full Width', 'Sitemap'));
$page_info_arr = array();
$page_info_arr['Page Templates'] = '
<p>We are providing the following page templates with this theme : <br>
	<ul style="margin-left:35px;">
		<li> Contact Us</li>
		<li> About Us</li>
		<li> Archives</li>
		<li> Short Codes</li>
		<li> Sitemap</li>
	</ul></p>
<p>You can create a page with a sidebar by using these page templates.</p>
<p>Follow the below steps to use this page tempate in your site : 
	<ul>
		<li>Go to the Dashboard of your site.</li>
		<li>Now, Go to Dashboard >> Pages >> Add New Page. </li>
		<li>Give a title of your choice. Now, you will see "Page Attribute" meta box in the right hand site of the page.<br/><br/>
			Looks like : &nbsp;&nbsp;<img src="'.$dummy_image_path.'add_page.png" >
		</li>
		<li>Now, select a Template from here.</li>
	</ul></p>';
	
$page_info_arr['Contact Us'] = '
<p>Simply designed page template to display a contact form. An easy to use page template to get contacted by the users directly via an email. You can use this page template the same way mentioned in "Page Templates" page. You just need to select <strong>Contact Us</strong> template to use it.</p>';

$page_info_arr['User Attending Event'] = '<p></p>';


$page_info_arr['About Us'] = "<p>An <strong>About Us</strong> page template where you can briefly write about the services you provide on your site.</p>
<br />
<strong>What we do?</strong><br /><p>An event is normally a large gathering of people, who have come to a particular place at a particular time for a particular reason. Having said that, there's very little that's normal about an event. In our experience, each one is different and their variety is enormous. And that's as it should be: an event is something special. Aone - off. We plan these occasions in meticulous details, manage them from the ground, dismantle them when they are over and assess the result.</p><br /> <strong>How we do it?</strong><br /> <p>Events can be used to communicate key message, faster community relations, motivate work forces or raise funds. One of the first things we ask our clients is, what they want to achieve from their event. This is the cornerstone of the whole operation for us, our starting point and most importantly, it's the way success can be measured.</p>";

$page_info_arr['Submit event'] = "Submit the events in category of your choice. [form_page_template post_type='event']";
$page_info_arr['Advanced Search'] = "[advance_search_page post_type='event']";

$page_info_arr['Archives'] = 'This is Archives page template. Just select <strong>Page - Archives</strong> page template from templates section and you&rsquo;re good to go.';

$page_info_arr['Sitemap'] =  '
See, how easy is to use page templates. Just add a new page and select <strong>Page - Sitemap</strong> from the page templates section. Easy peasy, isn&rsquo;t it.
';

$page_info_arr['Full Width'] = '

Do you know how easy it is to use Full Width page template ? Just add a new page and select full width page template and you are good to go. Here is a preview of this easy to use page template.

Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Praesent aliquam, justo convallis luctus rutrum, erat nulla fermentum diam, at nonummy quam ante ac quam. Maecenas urna purus, fermentum id, molestie in, commodo porttitor, felis. Nam blandit quam ut lacus.

Quisque ornare risus quis ligula. Phasellus tristique purus a augue condimentum adipiscing. Aenean sagittis. Etiam leo pede, rhoncus venenatis, tristique in, vulputate at, odio. Donec et ipsum et sapien vehicula nonummy. Suspendisse potenti. Fusce varius urna id quam. Sed neque mi, varius eget, tincidunt nec, suscipit id, libero. In eget purus. Vestibulum ut nisl. Donec eu mi sed turpis feugiat feugiat. Integer turpis arcu, pellentesque eget, cursus et, fermentum ut, sapien. Fusce metus mi, eleifend sollicitudin, molestie id, varius et, nibh. Donec nec libero.

Maecenas urna purus, fermentum id, molestie in, commodo porttitor, felis. Nam blandit quam ut lacus. Quisque ornare risus quis ligula. Phasellus tristique purus a augue condimentum adipiscing. Aenean sagittis. Etiam leo pede, rhoncus venenatis, tristique in, vulputate at, odio. Donec et ipsum et sapien vehicula nonummy. Suspendisse potenti. Fusce varius urna id quam. Sed neque mi, varius eget, tincidunt nec, suscipit id, libero. In eget purus. Vestibulum ut nisl. Donec eu mi sed turpis feugiat feugiat. Integer turpis arcu, pellentesque eget, cursus et, fermentum ut, sapien. Fusce metus mi, eleifend sollicitudin, molestie id, varius et, nibh. Donec nec libero.

Praesent aliquam, justo convallis luctus rutrum, erat nulla fermentum diam, at nonummy quam ante ac quam. Maecenas urna purus, fermentum id, molestie in, commodo porttitor, felis. Nam blandit quam ut lacus. Quisque ornare risus quis ligula. Phasellus tristique purus a augue condimentum adipiscing. Aenean sagittis. Etiam leo pede, rhoncus venenatis, tristique in, vulputate at, odio. Donec et ipsum et sapien vehicula nonummy. Suspendisse potenti. Fusce varius urna id quam. Sed neque mi, varius eget, tincidunt nec, suscipit id, libero. In eget purus. Vestibulum ut nisl. Donec eu mi sed turpis feugiat feugiat. Integer turpis arcu, pellentesque eget, cursus et, fermentum ut, sapien. Fusce metus mi, eleifend sollicitudin, molestie id, varius et, nibh. Donec nec libero.

Maecenas urna purus, fermentum id, molestie in, commodo porttitor, felis. Nam blandit quam ut lacus. Quisque ornare risus quis ligula. Phasellus tristique purus a augue condimentum adipiscing. Aenean sagittis. Etiam leo pede, rhoncus venenatis, tristique in, vulputate at, odio. Donec et ipsum et sapien vehicula nonummy. Suspendisse potenti. Fusce varius urna id quam. Sed neque mi, varius eget, tincidunt nec, suscipit id, libero. In eget purus. Vestibulum ut nisl. Donec eu mi sed turpis feugiat feugiat. Integer turpis arcu, pellentesque eget, cursus et, fermentum ut, sapien. Fusce metus mi, eleifend sollicitudin, molestie id, varius et, nibh. Donec nec libero.

See, there no sidebar in this template, and that why we call this a full page template. Yes, its this easy to use page templates. Just write any content as per your wish.
';

set_page_info_autorun($pages_array,$page_info_arr);
function set_page_info_autorun($pages_array,$page_info_arr_arg)
{
	global $post_author,$wpdb;
	$last_tt_id = 1;
	if(count($pages_array)>0)
	{
		$page_info_arr = array();
		for($p=0;$p<count($pages_array);$p++)
		{
			if(is_array($pages_array[$p]))
			{
				for($i=0;$i<count($pages_array[$p]);$i++)
				{
					$page_info_arr1 = array();
					$page_info_arr1['post_title'] = $pages_array[$p][$i];
					$page_info_arr1['post_content'] = $page_info_arr_arg[$pages_array[$p][$i]];
					$page_info_arr1['post_parent'] = $pages_array[$p][0];
					$page_info_arr[] = $page_info_arr1;
				}
			}
			else
			{
				$page_info_arr1 = array();
				$page_info_arr1['post_title'] = $pages_array[$p];
				@$page_info_arr1['post_content'] = $page_info_arr_arg[$pages_array[$p]];
				$page_info_arr1['post_parent'] = '';
				$page_info_arr[] = $page_info_arr1;
			}
		}

		if($page_info_arr)
		{
			for($j=0;$j<count($page_info_arr);$j++)
			{
				$post_title = $page_info_arr[$j]['post_title'];
				$post_content = addslashes($page_info_arr[$j]['post_content']);
				$post_parent = $page_info_arr[$j]['post_parent'];
				if($post_parent!='')
				{
					$post_parent_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts where post_title like \"$post_parent\" and post_type='page'");
				}else
				{
					$post_parent_id = 0;
				}
				$post_date = date('Y-m-d H:s:i');
				
				$post_name = strtolower(str_replace(array('&amp;',"'",'"',"?",".","!","@","#","$","%","^","&","*","(",")","-","+","+"," ",';',',','_','/'),array('','','','','','','','','','','','','','','','','','','','',',','','',''),$post_title));
				$post_name_count = $wpdb->get_var("SELECT count(ID) FROM $wpdb->posts where post_title=\"$post_title\" and post_type='page'");
				if($post_name_count>0)
				{
					$post_name = $post_name.'-'.($post_name_count+1);
				}
				$post_id_count = $wpdb->get_var("SELECT count(ID) FROM $wpdb->posts where post_title like \"$post_title\" and post_type='page'");
				if($post_id_count==0)
				{
					$post_sql = "insert into $wpdb->posts (post_author,post_date,post_date_gmt,post_title,post_content,post_name,post_parent,post_type) values (\"$post_author\", \"$post_date\", \"$post_date\",  \"$post_title\", \"$post_content\", \"$post_name\",\"$post_parent_id\",'page')";
					$wpdb->query($post_sql);
					$last_post_id = $wpdb->get_var("SELECT max(ID) FROM $wpdb->posts");
					$guid = home_url()."/?p=$last_post_id";
					$guid_sql = "update $wpdb->posts set guid=\"$guid\" where ID=\"$last_post_id\"";
					$wpdb->query($guid_sql);
					$ter_relation_sql = "insert into $wpdb->term_relationships (object_id,term_taxonomy_id) values (\"$last_post_id\",\"$last_tt_id\")";
					$wpdb->query($ter_relation_sql);
					update_post_meta( $last_post_id, 'pt_dummy_content', 1 );
					if($post_title =='Submit event'){
						update_post_meta( $last_post_id, 'is_tevolution_submit_form', '1' );
						update_post_meta( $last_post_id, 'submit_post_type', 'event' );
					}
					if($post_title =='User Attending Event'){
						update_post_meta( $last_post_id, '_wp_page_template', 'recurring_event_user.php' );
						update_option('recurring_event_page_template_id',$last_post_id);
					}
				}
			}
		}
	}
}

//Update the page templates
$page_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts where post_title like 'Advanced Search' and post_type='page'");
update_post_meta( $page_id, '_wp_page_template', 'default' );

$page_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts where post_title like 'Submit event' and post_type='page'");
update_post_meta( $page_id, '_wp_page_template', 'default' );
update_post_meta( $page_id, 'is_tevolution_submit_form', '1' );
update_post_meta( $page_id, 'submit_post_type', 'event' );

$page_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts where post_title like 'Contact Us' and post_type='page'");
update_post_meta( $page_id, '_wp_page_template', 'page-template-contact.php' );

$page_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts where post_title like 'Archives' and post_type='page'");
update_post_meta( $page_id, '_wp_page_template', 'page-template-archives.php' );

$page_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts where post_title like 'Full Width' and post_type='page'");
update_post_meta( $page_id, '_wp_page_template', 'default' );
update_post_meta( $page_id, 'Layout', '1c' );

$page_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts where post_title like 'Sitemap' and post_type='page'");
update_post_meta( $page_id, '_wp_page_template', 'page-template-sitemap.php' );

$page_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts where post_title like 'Short Codes' and post_type='page'");
update_post_meta( $page_id, '_wp_page_template', 'page-template-short_code.php' );

$page_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts where post_title like 'User Attending Event' and post_type='page'");
update_post_meta( $page_id, '_wp_page_template', 'recurring_event_user.php' );
update_option('recurring_event_page_template_id',$page_id);


//PAGE TEMPLATES END

/* ============================================== WIDGET SETTINGS START ================================================ */
 
$sidebars_widgets = get_option('sidebars_widgets');  //collect widget informations
$sidebars_widgets = array();

//FRONT CONTENT WIDGETS ======================================================
// 1. FRONT PAGE SLIDER
$templatic_slider = array();
$templatic_slider[1] = array(
					"title"					=>	'',
					"search"				=>	'1',
					"search_post_type"		=>	'event',
					"location"				=>	'1',
					"custom_banner_temp"			=>	'1',
					"s1"					=> array(get_stylesheet_directory_uri().'/images/dummy/inacup_donut.jpg',get_stylesheet_directory_uri().'/images/dummy/inacup_pumpkin.jpg',get_stylesheet_directory_uri().'/images/dummy/inacup_samoa.jpg',get_stylesheet_directory_uri().'/images/dummy/inacup_vanilla.jpg'),
					"display_text"	=>	'Looking for something to do? Search through %s events',
					"post_number"	=>	'5',
					);
$templatic_slider['_multiwidget'] = '1';
update_option('widget_templatic_slider',$templatic_slider);
$templatic_slider = get_option('widget_templatic_slider');
krsort($templatic_slider);
foreach($templatic_slider as $key1=>$val1)
{
	$templatic_slider_key = $key1;
	if(is_int($templatic_slider_key))
	{
		break;
	}
}

$sidebars_widgets["below_header"] = array("templatic_slider-$templatic_slider_key");

// 2. Event Listing calender WIDGET
$event_calendar = array();
$event_calendar[1] = array(
					"title"			=>	'Events Calendar',
					);
$event_calendar['_multiwidget'] = '1';
update_option('widget_event_calendar',$event_calendar);
$event_calendar = get_option('widget_event_calendar');
krsort($event_calendar);
foreach($event_calendar as $key1=>$val1)
{
	$event_calendar_key = $key1;
	if(is_int($event_calendar_key))
	{
		break;
	}
}

// 2. Event Listing calender WIDGET
$hybrid_categories = array();
$hybrid_categories[1] = array(
					"title"			=>	__('Categories',T_DOMAIN),
					"taxonomy"			=>	'ecategory',
					"hide_empty"			=>	'0',
					"show_count"			=>	'1',
					"number"			=>	'5',
					"style"			=>	'list',
					);
$hybrid_categories['_multiwidget'] = '1';
update_option('widget_hybrid-categories',$hybrid_categories);
$hybrid_categories = get_option('widget_hybrid-categories');
krsort($hybrid_categories);
foreach($hybrid_categories as $key1=>$val1)
{
	$hybrid_categories_key = $key1;
	if(is_int($hybrid_categories_key))
	{
		break;
	}
}


// 2. Event Listing calender WIDGET
$widget_comment = array();
$widget_comment[1] = array(
					"title"			=>	'Latest Reviews',
					"count"			=>	'3',
					"post_type"			=>	'event',
					);
$widget_comment['_multiwidget'] = '1';
update_option('widget_widget_comment',$widget_comment);
$widget_comment = get_option('widget_widget_comment');
krsort($widget_comment);
foreach($widget_comment as $key1=>$val1)
{
	$widget_comment_key = $key1;
	if(is_int($widget_comment_key))
	{
		break;
	}
}

$sidebars_widgets["ecategory_listing_sidebar"] = array("event_calendar-$event_calendar_key","hybrid-categories-$hybrid_categories_key","widget_comment-$widget_comment_key");
$sidebars_widgets["event_detail_sidebar"] = array("event_calendar-$event_calendar_key","hybrid-categories-$hybrid_categories_key","widget_comment-$widget_comment_key");
$sidebars_widgets["add_event_submit_sidebar"] = array("event_calendar-$event_calendar_key","hybrid-categories-$hybrid_categories_key","widget_comment-$widget_comment_key");
$sidebars_widgets["front_sidebar"] = array("event_calendar-$event_calendar_key","hybrid-categories-$hybrid_categories_key","widget_comment-$widget_comment_key");


// templatic_aboust_us widgets
$templatic_aboust_us = array();
$templatic_aboust_us[1] = array(
					"title"			=>	'',
					"about_us"			=>	"<p class='line01'>THE OVERSIZED PARAGRAPH</p><h2>No matter what the theme is about there is always place for a nice oversize paragraph here and there.</h2>"
					);
					
$templatic_aboust_us[2] = array(
					"title"			=>	'Contact Us At',
					"about_us"			=>	"230 Vine Street And locations throughout Old City, Philadelphia, PA 19106"
					);
$templatic_aboust_us['_multiwidget'] = '1';
update_option('widget_templatic_aboust_us',$templatic_aboust_us);
$templatic_aboust_us = get_option('widget_templatic_aboust_us');
krsort($templatic_aboust_us);
foreach($templatic_aboust_us as $key1=>$val1)
{
	$templatic_aboust_us_key = $key1;
	if(is_int($templatic_aboust_us_key))
	{
		break;
	}
}




if(is_plugin_active('Tevolution/templatic.php') && is_active_addons('custom_taxonomy') && is_active_addons('custom_fields_templates')){
	$catpost_category = 'nightlife';
	$catpost_category1 = 'exhibitions';
	$catpost_category2 = 'festivals';
	// 2. subsidiary area widgets
	$categoryposts = array();
	$categoryposts[1] = array(
						"title"			=>	'Nighlifes',
						"category"			=>	"$catpost_category",
						"post_number"			=>	'5',
						);
						
	$categoryposts[2] = array(
						"title"			=>	'Exhibitions',
						"category"			=>	"$catpost_category1",
						"post_number"			=>	'5',
						);
	$categoryposts[3] = array(
						"title"			=>	'Festivals',
						"category"			=>	"$catpost_category2",
						"post_number"			=>	'5',
						);

	$categoryposts['_multiwidget'] = '1';
	update_option('widget_categoryposts',$categoryposts);
	$categoryposts = get_option('widget_categoryposts');
	krsort($categoryposts);
	foreach($categoryposts as $key1=>$val1)
	{
		$categoryposts_key = $key1;
		if(is_int($categoryposts_key))
		{
			break;
		}
	}

	$sidebars_widgets["subsidiary"] = array("templatic_aboust_us-1","categoryposts-1","categoryposts-2","categoryposts-3");
}
// 2. footersearchwidget widgets
$footersearchwidget = array();
$footersearchwidget[1] = array(
					""			=>	'',
					);
$footersearchwidget['_multiwidget'] = '1';
update_option('widget_footersearchwidget',$footersearchwidget);
$footersearchwidget = get_option('widget_footersearchwidget');
krsort($footersearchwidget);
foreach($footersearchwidget as $key1=>$val1)
{
	$footersearchwidget_key = $key1;
	if(is_int($footersearchwidget_key))
	{
		break;
	}
}

$sidebars_widgets["footer1"] = array("footersearchwidget-$footersearchwidget_key");

// widget_subscribewidget widgets
$widget_subscribewidget = array();
$widget_subscribewidget[2] = array(
					'id' => '',
					'title' => '',
					'text' => 'Subscribe to our newsletter and get a weekly events schedule right in your inbox. It is free and we promise there will be no spam.',
					);
$widget_subscribewidget[3] = array(
					'id' => '',
					'title' => 'Newsletter',
					'text' => 'Events in your inbox',
					);
	
$widget_subscribewidget['_multiwidget'] = '1';
update_option('widget_widget_subscribewidget',$widget_subscribewidget);
$widget_subscribewidget = get_option('widget_widget_subscribewidget');
krsort($widget_subscribewidget);
foreach($widget_subscribewidget as $key1=>$val1)
{
	$widget_subscribewidget_key = $key1;
	if(is_int($widget_subscribewidget_key))
	{
		break;
	}
}

// social_media widgets
$social_media = array();
$social_media[1] = array(
					"title"			=>	'',
					"twitter"		=>	'http://www.twitter.com/templatic',
					"facebook"		=>	'http://www.facebook.com/templatic',
					"googleplus"	=>	'',
					"digg"			=>	'',
					"linkedin"		=>	'http://www.linkedin.com/templatic',
					"myspace"		=>	'',
					"rss"			=>	'http://templatic.com/feed',
					);
$social_media['_multiwidget'] = '1';
update_option('widget_templatic_social_media',$social_media);
$social_media = get_option('widget_templatic_social_media');
krsort($social_media);
foreach($social_media as $key1=>$val1)
{
	$social_media_key = $key1;
	if(is_int($social_media_key))
	{
		break;
	}
}

$sidebars_widgets["footer2"] = array("widget_subscribewidget-2","templatic_social_media-$social_media_key");




// hybrid-archives widgets
$hybrid_archives = array();
$hybrid_archives[1] = array(
					"title"			=>	'Archives',
					"type"			=>	'alpha',
					"format"			=>	'html',
					"limit"			=>	'5',
					);
$hybrid_archives['_multiwidget'] = '1';
update_option('widget_hybrid-archives',$hybrid_archives);
$hybrid_archives = get_option('widget_hybrid-archives');
krsort($hybrid_archives);
foreach($hybrid_archives as $key1=>$val1)
{
	$hybrid_archives_key = $key1;
	if(is_int($hybrid_archives_key))
	{
		break;
	}
}

// hybrid-pages widgets
$hybrid_pages = array();
$hybrid_pages[1] = array(
					"title"			=>	'Pages',
					"hierarchical"			=>	'1',
					"number"			=>	'5',
					);
$hybrid_pages['_multiwidget'] = '1';
update_option('widget_hybrid-pages',$hybrid_pages);
$hybrid_pages = get_option('widget_hybrid-pages');
krsort($hybrid_pages);
foreach($hybrid_pages as $key1=>$val1)
{
	$hybrid_pages_key = $key1;
	if(is_int($hybrid_pages_key))
	{
		break;
	}
}

// meta widgets
$meta = array();
$meta[1] = array(
					"title"			=>	'META',
					);
$meta['_multiwidget'] = '1';
update_option('widget_meta',$meta);
$meta = get_option('widget_meta');
krsort($meta);
foreach($meta as $key1=>$val1)
{
	$meta_key = $key1;
	if(is_int($meta_key))
	{
		break;
	}
}

$sidebars_widgets["footer3"] = array("hybrid-categories-$hybrid_categories_key","hybrid-archives-$hybrid_archives_key","hybrid-pages-$hybrid_pages_key","meta-$meta_key");


// 2. hybrid-search WIDGET
$hybrid_search = array();
$hybrid_search[1] = array(
					"title"			=>	'',
					"search_text"			=>	'Search this blog',
					);
$hybrid_search['_multiwidget'] = '1';
update_option('widget_hybrid-search',$hybrid_search);
$hybrid_search = get_option('widget_hybrid-search');
krsort($hybrid_search);
foreach($hybrid_search as $key1=>$val1)
{
	$hybrid_search_key = $key1;
	if(is_int($hybrid_search_key))
	{
		break;
	}
}

$sidebars_widgets["primary"] = array("hybrid-search-$hybrid_search_key","widget_subscribewidget-3","hybrid-categories-$hybrid_categories_key");


// 2. templatic_google_map WIDGET
$templatic_google_map = array();
$templatic_google_map[1] = array(
					"title"			=>	'Find Us On Map',
					"address"			=>	'230 Vine Street And locations throughout Old City, Philadelphia, PA 19106',
					"map_height"			=>	'300',
					"scale"			=>	'7',
					"map_type"			=>	'ROADMAP',
					);
$templatic_google_map['_multiwidget'] = '1';
update_option('widget_templatic_google_map',$templatic_google_map);
$templatic_google_map = get_option('widget_templatic_google_map');
krsort($templatic_google_map);
foreach($templatic_google_map as $key1=>$val1)
{
	$templatic_google_map_key = $key1;
	if(is_int($templatic_google_map_key))
	{
		break;
	}
}

$sidebars_widgets["contact_page_widget"] = array("templatic_aboust_us-2","templatic_google_map-$templatic_google_map_key");


update_option('sidebars_widgets',$sidebars_widgets);  //save widget iformations

$year_date = date('Y');

/////////////// WIDGET SETTINGS END ///////////////



/* ======================== CODE TO ADD RESIZED IMAGES ======================= */
regenerate_all_attachment_sizes();
 
function regenerate_all_attachment_sizes() {
	$args = array( 'post_type' => 'attachment', 'numberposts' => 100, 'post_status' => 'attachment'); 
	$attachments = get_posts( $args );
	if ($attachments) {
		foreach ( $attachments as $post ) {
			$file = get_attached_file( $post->ID );
			wp_update_attachment_metadata( $post->ID, wp_generate_attachment_metadata( $post->ID, $file ) );
		}
	}		
}

?>