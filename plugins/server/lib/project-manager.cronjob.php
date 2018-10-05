<?php

class rex_cronjob_project_manager_data extends rex_cronjob
{

    public function execute()
    {
        $websites = rex_sql::factory()->setDebug(0)->getArray('SELECT * FROM ' . rex::getTable('project_manager_domain') . ' ORDER BY updatedate asc'); 

        /* Addon-Abruf */
        $multi_curl = curl_multi_init();
        $resps = array();
        $options = array(
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_AUTOREFERER    => true, 
            CURLOPT_MAXREDIRS      => 3, 
            CURLOPT_HEADER         => false,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 1000
        );
        foreach($websites as $website) {
            $domain = $website['domain'];
            $cms = $website['cms'];
            
            $timestamp = time();
            
            $url = urlencode($domain)."?rex-api-call=project_manager&api_key=".$website['api_key'].'&t='.$timestamp;
            
            if ($cms == 5)
              $url = urlencode($domain)."?rex-api-call=project_manager&api_key=".$website['api_key'].'&t='.$timestamp;
            
            if ($cms == 4)
              $url = urlencode($domain)."/project_manager_client.php?rex-api-call=project_manager&api_key=".$website['api_key'].'&t='.$timestamp;

            $resps[$domain] = curl_init($url);
            curl_setopt_array($resps[$domain], $options);
            curl_multi_add_handle($multi_curl, $resps[$domain]);
        }
        $active = null;
        do {
            curl_multi_exec($multi_curl, $active);
        } while ($active > 0);

        foreach ($resps as $domain => $response) {
        	
            $resp = curl_multi_getcontent($response);
            curl_multi_remove_handle($multi_curl, $response);

            $json = json_decode($resp, true);

            $project_manager_domain = rex_sql::factory()->setDebug(0)->getArray('SELECT * FROM ' . rex::getTable('project_manager_domain') . ' WHERE domain = ? LIMIT 1', [$domain]); 
            
            if(json_last_error() === JSON_ERROR_NONE && $json !== null) {

              
              if ($json['status'] == 1) {
              
                rex_sql::factory()->setDebug(0)->setQuery('INSERT INTO ' . rex::getTable('project_manager_logs') . ' (`domain_id`, `createdate`, `raw`) VALUES(?,NOW(),?)', [$project_manager_domain[0]['id'],  $resp] );

                // SET STATUS
                rex_sql::factory()->setDebug(0)->setQuery("UPDATE " . rex::getTable('project_manager_domain') . " SET status = ? WHERE id = ?", [1, $project_manager_domain[0]['id']]);
              
              } else {
                
                // SET STATUS
                rex_sql::factory()->setDebug(0)->setQuery("UPDATE " . rex::getTable('project_manager_domain') . " SET status = ? WHERE id = ?", [0, $project_manager_domain[0]['id']]);
                
              }
              
            } else {
            
              
               // SET STATUS
               rex_sql::factory()->setDebug(0)->setQuery("UPDATE " . rex::getTable('project_manager_domain') . " SET status = ? WHERE id = ?", [-1, $project_manager_domain[0]['id']]);
            }
            
            rex_sql::factory()->setDebug(0)->setQuery("UPDATE " . rex::getTable('project_manager_domain') . " SET logdate = NOW() WHERE id = ?", [$project_manager_domain[0]['id']]);

        }
        
        curl_multi_close($multi_curl);

        return true;

    }
    public function getTypeName()
    {
        return rex_i18n::msg('project_manager_cronjob_data_name');
    }

    public function getParamFields()
    {
        return [];
    }
}
?>