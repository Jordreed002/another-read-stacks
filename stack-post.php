<?php
/**
 * The template for displaying stacks in individual posts
 *
 */
  
$options = get_option('another_read_stacks_settings');
get_header(); ?>
  
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

        <?php 
            $current_post = get_post($post, ARRAY_A, 'display');

            $title = $current_post['post_title'];
            $stackContent = get_post_meta($post->ID, '_stack_content', true);
        ?>
  
        <div class="another-read-stack-post">
            <div class="stack-title">
                <h1><?php echo $title; ?></h1>
            </div>
            <div class="stack-content">
            <?php foreach($stackContent['book_list'] as $stack){ ?>

                <div class="stack">
                    <div class="book-image">
                        <a href="<?php echo $stack['book_link'] ?>"><img src="<?php echo $stack['jacket_image']; ?>" alt="<?php echo $stack['book_name']; ?>"></a>
                    </div>
                    <div class="book-text">
                        <p><?php if(isset($options['keynote']) && $options['keynote'] == '1'){echo $stack['keynote'];} ?></p>
                    </div>
                    <div class="book-info">
                        <div class="activity-book-title">
                            <a href="<?php echo $stack['book_link']; ?>">
                                <p><?php echo $stack['book_name']; ?></p>
                            </a>
                        </div>

                        <?php foreach($stack['contributors'] as $contributor){ ?>
                        <div class="stbook-author">
                            <a href="<?php echo $contributor['author_link']; ?>">
                                <p><?php echo $contributor['author_name']; ?></p>
                            </a>
                        </div>

                        <?php } ?>

                    </div>
                </div>
                <?php } ?>

            </div>
        </div>

  
        </main><!-- .site-main -->
    </div><!-- .content-area -->
  
<?php get_footer(); ?>