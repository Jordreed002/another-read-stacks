<?php 

class AnotherReadStacksCPT{

    static function stacksCPT(){

        //custom post type
        register_post_type('stacks',
        array(
            'labels' => array(
                'name' => 'Stacks',
                'singular_name' => 'Stack',
                'add_new' => 'Add Stack',
                'all_items' => 'All Stacks',
                'add_new_item' => 'Add Stack',
                'edit_item' => 'Edit Stack',
                'new_item' => 'New Stack',
                'view_item' => 'View Stack',
                'search_item' => 'Search Stacks',
                'not_foud' => 'No Stacks found',
                'not_found_in_trash' => 'No Stacks found in trash'
            ),
            'public' => true,
            'hierarchical' => false,
            'has_archive' => false,
            'exclude_from_search' => false,
            'show_in_rest' => true
            )
        );

        //removes editor from posts
        remove_post_type_support('stacks', 'editor');
        remove_post_type_support('stacks', 'author');
    }

    static function createMetaBoxes(){

        //stack meta box
        add_meta_box(
            'stack_data_id',
            'Stack content',
            array(self::class, 'stack_data_html'),
            'stacks'
        );
    }
        
    static function stack_data_html($post){

        //Obtains the values for the metaboxes
        $postMeta = get_post_meta($post->ID, '_stack_content', true);


            //html for the meta boxes
            ?>
                <div class="meta-container">
                    <div class="meta-label">
                        <label for="stack-id">Stack ID</label>
                    </div>
                    <div class="meta-input">
                        <input type="text" name="stack_id" value="<?php echo $post->ID ?>" id="stack_id">
                    </div>
                </div>
        <?php
        $i = 1;
        //Loops through the array and sets the values for the metaboxes
        foreach($postMeta['book_list'] as $metaData){

        ?>
                <div class="header"> <h1>Book <?php echo $i ?></h1></div>
                <div class="meta-container">
                    <div class="meta-label">
                        <label for="book-name">Book name</label>
                    </div>
                    <div class="meta-input">
                        <input class="regular-text" type="text" name="book_<?php echo $i; ?>_book_name" value="<?php echo $metaData['book_name'] ?>" id="book_name">
                    </div>
                </div>
                <div class="meta-container">
                    <div class="meta-label">
                        <label for="jacket-image">Link to jacket image</label>
                    </div>
                    <div class="meta-input">
                        <input class="regular-text" type="text" name="book_<?php echo $i; ?>_jacket_image" value="<?php echo $metaData['jacket_image'] ?>" id="jacket_image">
                    </div>
                </div>
                <div class="meta-container">
                    <div class="meta-label">
                        <label for="keynote">Keynote</label>
                    </div>
                    <div class="meta-input">
                        <input class="regular-text" type="text" name="book_<?php echo $i; ?>_keynote" value="<?php echo $metaData['keynote'] ?>" id="keynote">
                    </div>
                </div>
                <div class="meta-container">
                    <div class="meta-label">
                        <label for="book-isbn">Book ISBN</label>
                    </div>
                    <div class="meta-input">
                        <input class="regular-text" type="text" name="book_<?php echo $i; ?>_book_isbn" value="<?php echo $metaData['book_isbn'] ?>" id="book_isbn">
                    </div>
                </div>

                <div class="meta-container">
                    <div class="meta-label">
                        <label for="book-link">Link to book</label>
                    </div>
                    <div class="meta-input">
                        <input class="regular-text" type="text" name="book_<?php echo $i; ?>_book_link" value="<?php echo $metaData['book_link'] ?>" id="book_link">
                    </div>
                </div>
                <?php 
                $j = 0;
                foreach($metaData['contributors'] as $contributor) { ?>
                <div class="meta-container">
                    <div class="meta-label">
                        <label for="author-name">Author name</label>
                    </div>
                    <div class="meta-input">
                        <input class="regular-text" type="text" name="book_<?php echo $i; ?>_author_name_<?php echo $j; ?>" value="<?php echo $contributor['author_name'] ?>" id="author_name">
                    </div>
                </div>
                <div class="meta-container">
                    <div class="meta-label">
                        <label for="author-link">Link to author</label>
                    </div>
                    <div class="meta-input">
                        <input class="regular-text" type="text" name="book_<?php echo $i; ?>_author_link_<?php echo $j; ?>" value="<?php echo $contributor['author_link'] ?>" id="author_link">
                    </div>
                </div>
        <?php
                }
            $i++;
        }
    }

    static function saveStacksMetaBoxes($post_id){


        if( array_key_exists('stack_id', $_POST) && $_POST['stack_id'] == $post_id){
            $i = 1;
            $k = 0;
            $meta = get_post_meta($post_id, '_stack_content', true);

            //Loops through the current meta array and sets the new values for the metaboxes
            foreach($meta['book_list'] as $book){
                $book['book_name'] = $_POST['book_'.$i.'_book_name'];
                $book['jacket_image'] = $_POST['book_'.$i.'_jacket_image'];
                $book['keynote'] = $_POST['book_'.$i.'_keynote'];
                $book['book_isbn'] = $_POST['book_'.$i.'_book_isbn'];
                $book['book_link'] = $_POST['book_'.$i.'_book_link'];
                $book['contributors'] = array();
                $j = 0;
                foreach($book['contributors'] as $contributor){
                    $contributor[$j] = array(
                        'author_name' => $_POST['book_'.$i.'_author_name_'.$j],
                        'author_link' => $_POST['book_'.$i.'_author_link_'.$j]
                    );
                    $j++;
                }

                $stackContent['book_list'][$i] = $book;

                $i++;
                $k++;
            }

            $meta['book_list'] = $stackContent['book_list'];
            
            update_post_meta(
                $post_id,
                '_stack_content',
                $meta
            );
        }
        
    }

    static function setTemplate($single_template){
        global $post;

        if($post->post_type == 'stacks'){
            $single_template = dirname(__FILE__) . '/stack-post.php';

            return $single_template;
        }
        else{
            return $single_template;
        }
    }

}


?>