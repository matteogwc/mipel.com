<?php
include("lib/Mobile_Detect.php");
include('inc/lang.php');

$detect = new Mobile_Detect();
$isMobile = $detect->isMobile();
$isTablet = $detect->isTablet();

    @session_start();
    
    if(!isset($_SESSION['desktop'])) {
        $_SESSION['desktop'] = 0;
    }
     

    if($isMobile && isset($_GET['desktop'])) {
        $_SESSION['desktop'] = 1;
    } 

    if( $isMobile && !$isTablet && $_SESSION['desktop'] == 0) {
    	
    	// se l'utente si trova nella pagina catalogo, lo ridireziono alla versione mobile della stessa.
    	if( strpos($_SERVER['REQUEST_URI'], 'catalogo/') !== false ) {
    		
    		// se settata mantendo la qs in modo da andare direttamente alla pagina dell'espositore
    		if( ! empty($_GET['show']) ) {
    			$redirectUrl .= 'http://www.mipel.com/mobile/catalogo_show.php?id=' . $_GET['show'];
    		} else {
    			$redirectUrl = 'http://www.mipel.com/mobile/catalogo.php';
    		}
    	}
    	else {
    		$redirectUrl = 'http://www.mipel.com/mobile/';
    	}
    	
        header('refresh:0;url='.$redirectUrl);
        die();
    }


require("api/lib/news.class.php");

load_theme_textdomain('mipel'); 

$templateName = get_page_template_name();

//echo $templateName;

//include("inc/lang.php");


global $mp, $mp_status, $newslist;




if($templateName == 'page-registrazione' || $templateName == 'page-prereg-visitatori' || $templateName == 'page-prereg-press') {



    require_once('lib/mipel.registrazioni.class.php');

    $mp_status = null;
    $mp = new MipelRegistrazioni();

    //print_r($_POST);    



    if(isset($_POST['richiesta'])){

        //print_r($_POST);
	

        $_SESSION = $_POST;

        $mp_status = $mp->inviaRichiestaEspositore($_POST);
	wp_redirect('http://www.mipel.com/espositori-section/richiesta-inviata/');

        //print_r($status);

    }

    if( isset($_POST['register']) ){
    
        $_SESSION = $_POST;
        $mp_status = $mp->addVisitor($_POST, $language);
        //print_r($status);
    }

    if(isset($_POST['register-press'])){


        $_SESSION['press'] = $_POST;
        $mp_status = $mp->addPress($_POST);
    }

}



if($templateName == 'page-catalogo') {



    include('lib/mipel.catalogo.class.php');

    $mp = new Catalogo();



    function fileExists($fileName, $caseSensitive = true) {



        if(file_exists($fileName)) {

            return $fileName;

        }

        if($caseSensitive) return false;



        // Handle case insensitive requests            

        $directoryName = dirname($fileName);

        $fileArray = glob($directoryName . '/*', GLOB_NOSORT);

        $fileNameLowerCase = strtolower($fileName);

        foreach($fileArray as $file) {

            if(strtolower($file) == $fileNameLowerCase) {

                return $file;

            }

        }

        return false;

    }

}

if(is_home()){
    $news = new News();
    $newslist = $news->getList(10);
    //print_r($newslist);
}

?>

<!DOCTYPE html>

<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->

<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->

<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->

<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

    <head>

        <meta charset="utf-8">

        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

        <title><?php wp_title( '|', true, 'right' );?></title>

        <meta name="description" content="">




        <link rel="stylesheet" href="<?php bloginfo('template_directory');?>/css/normalize.min.css">

        <link rel="stylesheet" href="<?php bloginfo('template_directory');?>/css/main.css">

        <link rel="stylesheet" href="<?php bloginfo('template_directory');?>/css/custom.css">

        <link rel="stylesheet" href="<?php bloginfo('template_directory');?>/css/lightbox.css"/>

        <link rel="stylesheet" href="<?php bloginfo('template_directory');?>/css/skitter.styles.min.css"/>

		<link rel="stylesheet" href="//releases.flowplayer.org/5.4.3/skin/minimalist.css">

        <script src="<?php bloginfo('template_directory');?>/js/vendor/modernizr-2.6.2.min.js"></script>

        <?php wp_head(); ?>

    </head>



    <!--[if gte IE 9]>

      <style type="text/css">

        .gradient {

           filter: none;

        }

      </style>

    <![endif]-->

    <body class="gradient">

        <div id="top-panel">

            <div class="wrapper">

                <div class="colonna" id="social" style="padding-top:8px;">

                    <a href="http://www.facebook.com/pages/MIPEL/199258056810278" target="_blank"><img src="<?php bloginfo('template_directory');?>/img/contatti_social_facebook.png" /></a>&nbsp;&nbsp;

                    <a href="https://twitter.com/thebagshow" target="_blank"><img src="<?php bloginfo('template_directory');?>/img/contatti_social_twitter.png"/></a>&nbsp;&nbsp;

                    <a href="http://www.youtube.com/channel/UCFUhYgTFmBZHQ0VKbd0SY9g" target="_blank"><img src="<?php bloginfo('template_directory');?>/img/contatti_social_youtube.png" /></a>

                </div>

                <div class="colonna" id="secondary_menu">

                    <nav id="second">                    	

                        <?php wp_nav_menu( array('container' => false, 'theme_location' => 'top_menu') ); ?>                    	

                        <!--

                        <ul>

                            <li class="icon"><a href="http://www.mipel.com/it/" title=""><img src="<?php bloginfo('template_directory');?>/img/flag_ita.gif" alt="<?php echo _('Italiano')?>"/></a></li>

                            <li class="last icon"><a href="http://www.mipel.com/en/" title=""><img src="<?php bloginfo('template_directory');?>/img/flag_eng.gif" alt="<?php echo _('English')?>"/></a></li>

                        </ul>

                        -->

                        <?php echo qtrans_generateLanguageSelectCode('image'); ?>

                    </nav>

                </div>

            </div>

        </div>

        <header>

            <div class="wrapper">

                <h1 class="logo">

                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="">

                        <img src="<?php bloginfo('template_directory');?>/img/logo_mipel.png" alt="MIPEL"/>

                    </a>

                </h1>

            </div>

        </header>

        <nav id="main">

            <div class="wrapper">

                <?php wp_nav_menu( array('container' => false, 'theme_location' => 'main_menu') ); ?>

            </div>

        </nav>

        