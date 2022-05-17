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
                        <input type="text" name="stack-id" value="<?php echo $post->ID ?>" id="stack-id">
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
                        <input class="regular-text" type="text" name="book-name" value="<?php echo $metaData['book_name'] ?>" id="book-name">
                    </div>
                </div>
                <div class="meta-container">
                    <div class="meta-label">
                        <label for="jacket-image">Link to jacket image</label>
                    </div>
                    <div class="meta-input">
                        <input class="regular-text" type="text" name="jacket-image" value="<?php echo $metaData['jacket_image'] ?>" id="jacketImage">
                    </div>
                </div>
                <div class="meta-container">
                    <div class="meta-label">
                        <label for="keynote">Keynote</label>
                    </div>
                    <div class="meta-input">
                        <input class="regular-text" type="text" name="keynote" value="<?php echo $metaData['keynote'] ?>" id="keynote">
                    </div>
                </div>
                <div class="meta-container">
                    <div class="meta-label">
                        <label for="book-isbn">Book ISBN</label>
                    </div>
                    <div class="meta-input">
                        <input class="regular-text" type="text" name="book-isbn" value="<?php echo $metaData['book_isbn'] ?>" id="book-isbn">
                    </div>
                </div>

                <div class="meta-container">
                    <div class="meta-label">
                        <label for="book-link">Link to book</label>
                    </div>
                    <div class="meta-input">
                        <input class="regular-text" type="text" name="book-link" value="<?php echo $metaData['book_link'] ?>" id="book-link">
                    </div>
                </div>
                <?php foreach($metaData['contributors'] as $contributor) { ?>
                <div class="meta-container">
                    <div class="meta-label">
                        <label for="author-name">Author name</label>
                    </div>
                    <div class="meta-input">
                        <input class="regular-text" type="text" name="author-name" value="<?php echo $contributor['author_name'] ?>" id="author-name">
                    </div>
                </div>
                <div class="meta-container">
                    <div class="meta-label">
                        <label for="author-link">Link to author</label>
                    </div>
                    <div class="meta-input">
                        <input class="regular-text" type="text" name="author-link" value="<?php echo $contributor['author_link'] ?>" id="author-link">
                    </div>
                </div>
        <?php
                }
            $i++;
        }
    }

    static function saveMetaBoxes(int $post_id){

        $stackContent = array();

        if( array_key_exists('stack_content', $_POST)){                
            update_post_meta(
                $post_id,
                '_stack_content',
                $_POST[$stackContent]
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