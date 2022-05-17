<?php

    class AnotherReadApi {

        static function APIcall($endpoint, $data){

            $options = get_option('another_read_settings');
    
    
            $endpoints = array("login" => "https://anotherread.com/api/user/json/v1/get-api-key/default.aspx",
                               "get_stacks" => "https://anotherread.com/site/read/templates/api/stacks/json/v2/get-stack-admin-list/default.aspx",
                            );
        
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $endpoints[$endpoint]);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $headers = array(
                "Accept: application/json"
            );
        
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    
    
        
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        
            $resp = curl_exec($curl);
            curl_close($curl);
        
            $activityRepsonse = json_decode($resp, true);
    
            if($activityRepsonse["ApiCallWasSuccessful"] == true)
            {
                $timestamp = new DateTime();
                if(get_option('another_read_settings_timestamp') !== false){
                    update_option('another_read_settings_timestamp', $timestamp);
                }
                else{
                    add_option('another_read_settings_timestamp', $timestamp);
                }
                //print_r("there was no error");
                $options['apiCallSuccessful'] = true;
                update_option('another_read_settings', $options);
                return $activityRepsonse;
            }
            else{
                
                //print_r("there was an error");
                $options['apiCallSuccessful'] = false;
                update_option('another_read_settings', $options);
                return $activityRepsonse;
            }
        
        }
    }

?>
