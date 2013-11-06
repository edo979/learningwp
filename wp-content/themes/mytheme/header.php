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
    <title><?php wp_title('|', true, 'right'); ?></title>
    
    <?php wp_head(); ?>
  </head>

  <body <?php body_class(); ?>>
    <nav class="navbar navbar-inverse navbar-static-top" role="navigation"> 
      <!-- Brand and toggle get grouped for better mobile display --> 
      <div class="navbar-header"> 
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-ex1-collapse"> 
          <span class="sr-only">Toggle navigation</span> 
          <span class="icon-bar"></span> 
          <span class="icon-bar"></span> 
          <span class="icon-bar"></span> 
        </button> 
        <a class="navbar-brand" href="<?php bloginfo('url') ?>"><?php bloginfo('name') ?></a>
      </div> 
      <!-- Collect the nav links, forms, and other content for toggling --> 
      <div class="collapse navbar-collapse" id="navbar-ex1-collapse">
        <?php
        /* Primary navigation */
        wp_nav_menu(array(
            'menu'       => 'primary',
            'depth'      => 2,
            'container'  => false,
            'menu_class' => 'nav navbar-nav',
            //Process nav menu using our custom nav walker
            'walker'     => new wp_bootstrap_navwalker())
        );
        ?>
      </div>
    </nav>