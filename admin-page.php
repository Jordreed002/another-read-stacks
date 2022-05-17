<?php
/*
*   Admin page
*/

    class AnotherReadAdmin{

        //page that is displayed in wp-admin
        static function adminPage(){

            $options = get_option('another_read_stacks_settings');

            if(isset($_POST['update_settings']) || isset($_POST['update_posts'])){ echo '<div class="notice notice-success settings-error"><p>Settings were updated successfuly</p></div>';}
            if(isset($_POST['update_posts']) && $options['apiCallSuccessful'] == false){ echo '<div class="error"><p>There was an error with the API call. Please check your settings and try again.</p></div>';}
            if(isset($_POST['update_posts']) && $options['apiCallSuccessful'] == true){ echo '<div class="notice notice-success settings-error"><p>API call was successful and activity posts were gathered.</p></div>';}
            
            
            ?>
            <div class="another-read-admin">
                <div class="another-read-admin-settings">
                    <div class="admin-header">
                        <h1>Another Read</h1>
                        <h2>Stacks Settings</h2>
                    </div>
                    <div class="another-read-activity-settings">
                        <form action="" method="post">
                            <table class="form-table">
                                <tbody>
                                    <?php if($options['logged_in'] == false){ ?>
                                    <tr>
                                        <th scope="row">
                                            <label for="username">Another Read Username</label>
                                        </th>
                                        <td>
                                            <input type="text" id="username" name="username" value="" class="regular-text">
                                        </td>
                                    </tr>
                                    <tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="password">Another Read Password</label>
                                        </th>
                                        <td>
                                            <input type="password" id="password" name="password" value="" class="regular-text">
                                        </td>
                                    </tr>
                                    <?php }?>
                                    <tr>
                                        <th scope="row">
                                            <label for="login">Another Read account status</label>
                                        </th>
                                        <td>
                                            <?php if($options['logged_in'] == false){ ?>
                                            <input class="button button-primary" name="ar_login" type="submit" value="Login">
                                            <?php }
                                            elseif($options['logged_in'] == true && isset($options['usertokenExpiry'])){ ?>
                                                <p>Login expires on: </p> <?php echo $options['usertokenExpiry']; ?>
                                                <br>
                                                <input class="button button-primary" name="ar_logout" type="submit" value="Logout">
                                            <?php } ?>

                                        </td>
                                    </tr>
                                    <?php if($options['logged_in'] == true){ ?>
                                    <tr>
                                        <th scope="row">
                                            <label for="accesskey">Another Read API key</label>
                                        </th>
                                        <td>
                                            <input class="regular-text" type="text" id="apiKey" name="apiKey" value="<?php if(isset($options['apiKey'])){echo $options['apiKey'];} ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="keynote">Display keynote for stacks</label>
                                        </th>
                                        <td>
                                            <input name="keynote" type="hidden" value="0">
                                            <input name="keynote" id="keynote" type="checkbox" value="1" <?php checked('1', $options['keynote']) ?> >

                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <h4>Last updated</h4>
                                        </th>
                                        <td>
                                            <p><?php if(isset($options['timestamp'])){ echo $options['timestamp']->format('Y-m-d H:i');}else{ echo "There has been no updates";} ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <input type="submit" name="update_settings_stacks" class="button button-primary" value="Save settings" >
                                        </th>
                                        <td>
                                        <input type="submit" name="update_posts_stacks" class="button button-primary" value="Update stacks" >

                                        </td>
                                    </tr>
                                    <?php }?>
                                </tbody>
                            </table>

                        </form>                  
                    </div>
                </div>
                <div class="another-read-content">
                    <div class="another-read-banner">
                        <div class="branding">
                            <a href="https://anotherread.com" target="_blank">
                                <img class="branding-img" src="<?php echo plugin_dir_url(__FILE__) . 'img/brand--extended--red.svg'; ?>" alt="Another Read">
                            </a>
                        </div>
                        <div class="banner-content">
                            <h2>Find children's books you like and we will recommend others.</h2>
                            <p>Our app helps parents and young readers discover the latest children's books as well as all-time favourites. We make topical recommendations based on the books readers like and bring them news and events from their favourite authors and illustrators</p>
                            <img class="content-img" src="<?php echo plugin_dir_url(__FILE__) . 'img/home-app-device-mock-up.jpg'; ?>" alt="">
                        </div>
                    </div>
                    <div class="another-read-faq">
                        <h2>F.A.Q</h2>
                    </div>
                </div>

            <?php

                // $url = "https://anotherread.com/api/user/json/v1/get-api-key/default.aspx";
                    
                // $curl = curl_init();
                // curl_setopt($curl, CURLOPT_URL, $url);
                // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                // $headers = array(
                //     "Accept: application/json"
                // );

                // curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

                // $data = array(

                //     "username" => "jordan@lineindustries.com",
                //     "password" => '154956',
                // );

                // curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

                // $resp = curl_exec($curl);
                // curl_close($curl);

                // $activityRepsonse = json_decode($resp, true);

        }

    }



?>