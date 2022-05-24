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

            function arrayKeyCheck($key){
                if(isset($key)){
                   return $key;
                }
                else{
                    return '';
                }
            }
            
            foreach($stackResults as $stack ){
                if( get_post($stack['StackID']) == false){

                    $stackResult = $stackPayload['Payload'];

                    $title = arrayKeyCheck($stack['Title']);
                    $stackID = arrayKeyCheck($stack['StackID']);
                    $bookList = arrayKeyCheck($stack['BookList']);

                    $metaInput = array(
                        '_stack_content' => array(
                            'title' => $title,
                            'stack_id' => $stackID,
                            'book_list' => array()
                        ),
                    );

                    $i = 0;
                    foreach($bookList as $book){

                        $bookLookup = arrayKeyCheck($stackResult['BookLookup'][$book]);
                        $jacketImage = arrayKeyCheck($bookLookup['JacketUrl']);
                        $keynote = arrayKeyCheck($bookLookup['Keynote']);
                        $bookName = arrayKeyCheck($bookLookup['Title']);
                        $bookLink = arrayKeyCheck($bookLookup['BookLink']);
                        $contributors = arrayKeyCheck($bookLookup['Contributors']);
                        $contributor = array();

                        $j = 0;
                        foreach($contributors as $contributorID){
                            $contributorLookup = arrayKeyCheck($stackResult['ContributorLookup'][$contributorID]);
                            $authorName = arrayKeyCheck($contributorLookup['DisplayName']);
                            $authorLink = arrayKeyCheck($contributorLookup['ContributorLink']);

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