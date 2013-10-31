<?php
/* =============================================================
 * single.php
 * =============================================================
 * Displays content for a single blog post
 * ============================================================= */
?>

<?php get_header(); ?>
        <?php get_template_part( 'featured', 'bar' ); ?>

        <div class="row">
            <div class="span8">
                <div id="main_content" role="main">
                
                    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    
                        <h2 class="page-title"><?php the_title(); ?></h2>
                        <span class="meta">written by <?php the_author_link(); ?> on <?php the_time('F j, Y'); ?> in <?php the_category( ' and ' ); ?> with <a href="<?php comments_link(); ?>" title="<?php comments_number( 'no comments', 'one comment', '% comments' ); ?>"><?php comments_number( 'no comments', 'one Comment', '% comments' ); ?></a></span>
                        <div class="prev-next-single clearfix">
                            <span class="prev"><?php previous_post_link( '%link', '&#x2190; older posts' ); ?></span>
                            <span class="next"><?php next_post_link( '%link', 'newer posts &#x2192;' ); ?></span>
                        </div>
                        
                        <?php the_content(); ?>
                        <?php the_tags( '<span class="post-tags"><span class="meta">tags:</span> ', ' ', '</span>' ); /* &#8226; */ ?>
                        <?php wp_link_pages( array(
                            'before' => '<hr class="hr-row-divider"><p class="wp-link-pages hero-p">Continue Reading: ',
                            'after' => '</p>'
                        )); ?>
                        <?php get_template_part( 'related', 'posts' ); ?>
				    
                    <?php endwhile; else: ?>
				    
                        <p class="hero-p" style="padding: 30px 0;">there is currently nothing to display :(</p>
				    
                    <?php endif; ?>
                    
                </div><!-- end #main_content -->
                <hr class="hr-row-divider">
                
                    <?php comments_template(); ?>
                
                <hr class="hr-row-divider">
            </div><!-- end .span8 -->
                    
            <?php get_sidebar( 'main' ); ?>
            
        </div><!-- end .row -->

<?php get_footer(); ?>