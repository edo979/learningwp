<?php
/**
 * The template for displaying Search form
 */
 ?>

<div class="row">
  <div class="col-lg-12">
    <form action="/" method="get" class="form-inline" role="form">
      <div class="input-group">
        <label class="sr-only" for="search input">Search</label>
        <input type="text" name="s" id="search" class="form-control" value="<?php the_search_query(); ?>" />
        <span class="input-group-btn">
          <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span></button>
        </span><!-- .input-group-btn-->
      </div><!-- .input-group -->
    </form><!-- form -->
  </div><!-- .col-lg-12 -->
</div><!-- .row -->