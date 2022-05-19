<?php 

class AnotherReadStacksPostCreator{

    static function create(){

        $options = get_option('another_read_stacks_settings');
        $data = array(
            'accesskey' => $options['apiKey'],
            'usertoken' => $options['usertoken'],
            'pagenumber' => 1,
            'pagesize' => 10,
            'includebooks' => 'true',
            'includeratings' => 'true',
        );

        $stackPayload = AnotherReadApi::APIcall("get_stacks", $data);


        if($stackPayload['ApiCallWasSuccessful'] == true){

            $stackResults = $stackPayload['Payload']['Result']['Results'];
            
            foreach($stackResults as $stack ){
                if( get_post($stack['StackID']) == false){

                    $stackResult = $stackPayload['Payload'];

                    $title = $stack['Title'];
                    $stackID = $stack['StackID'];
                    $bookList = $stack['BookList'];

                    $metaInput = array(
                        '_stack_content' => array(
                            'title' => $title,
                            'stack_id' => $stackID,
                            'book_list' => array()
                        ),
                    );

                    $i = 0;
                    foreach($bookList as $book){

                        $bookLookup = $stackResult['BookLookup'][$book];
                        $jacketImage = $bookLookup['JacketUrl'];
                        $keynote = $bookLookup['Keynote'];
                        $bookName = $bookLookup['Title'];
                        $bookLink = $bookLookup['BookLink'];
                        $contributors = $bookLookup['Contributors'];
                        $contributor = array();

                        $j = 0;
                        foreach($contributors as $contributorID){
                            $contributorLookup = $stackResult['ContributorLookup'][$contributorID];
                            $authorName = $contributorLookup['DisplayName'];
                            $authorLink = $contributorLookup['ContributorLink'];

                            $contributor[$j] = array(
                                'author_name' => $authorName,
                                'author_link' => $authorLink
                            );
                            
                            $j++;

                        }

                        $book = array(
                            'jacket_image' => $jacketImage,
                            'book_isbn' => $book,
                            'book_name' => $bookName,
                            'book_link' => $bookLink,
                            'keynote' => $keynote,
                            'contributors' => $contributor,
                        );



                        $metaInput['_stack_content']['book_list'][$i] = $book;
                        $i++;
                    }

                    $stackPost = array(
                        'post_title'    => wp_strip_all_tags( $title ),
                        'post_status'   => 'publish',
                        'post_type'     => 'stacks',
                        'meta_input'    => $metaInput,
                        'import_id'     => $stackID
                    );
                    
                    wp_insert_post($stackPost);
                    //print_r('post created');



                }

            }
        }
            $timestamp = new DateTime();
            if(isset($settings['timestamp'])){
                $options['timestamp'] = $timestamp;
                update_option('another_read_stacks_settings', $options);
            }
            else{
                $options['timestamp'] = $timestamp;
                update_option('another_read_stacks_settings', $options);
            }
        

    }
}


?>