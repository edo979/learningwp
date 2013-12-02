<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
  <!--<![endif]-->
  <head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width">
    <title><?php bloginfo('name'); ?> | <?php bloginfo('description'); ?></title>
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

    <?php wp_head(); ?>
  </head>

  <body <?php body_class(); ?>>
    <div id="main-container" class="container">
      <nav class="navbar navbar-default" role="navigation"> 
        <!-- Brand and toggle get grouped for better mobile display --> 
        <div class="navbar-header"> 
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-ex1-collapse"> 
            <span class="sr-only">Toggle navigation</span> 
            <span class="icon-bar"></span> 
            <span class="icon-bar"></span> 
            <span class="icon-bar"></span> 
          </button> 
        </div> 
        <!-- Collect the nav links, forms, and other content for toggling --> 
        <div class="collapse navbar-collapse" id="navbar-ex1-collapse">
          <?php
          /* Primary navigation */
          wp_nav_menu(array(
              'menu'           => 'primary',
              'theme_location' => 'primary',
              'depth'          => 2,
              'container'      => false,
              'menu_class'     => 'nav navbar-nav',
              //Process nav menu using our custom nav walker
              'walker'         => new wp_bootstrap_navwalker())
          );
          ?>
        </div>
      </nav>

      <!-- Carousel
      ================================================== -->
      <div id="mytheme-carousel" class="carousel slide" data-ride="carousel">
        <?php
        // Get slides from transient
        if ($slides = get_transient('slides_order_result'))
        {
          // Show slides from transient cache
          echo $slides;
        }
        else
        {
          // Set default order
          easytheme_slides_save_order();
          if ($slides = get_transient('slides_order_result'))
          {
            echo $slides;
          }
          else
          {
            echo 'sory no slides found';
          }
        }
        ?>

        <a class="left carousel-control" href="#mytheme-carousel" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
        <a class="right carousel-control" href="#mytheme-carousel" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
      </div><!-- /.carousel -->