<?php
run_request(0);

function build_photos($photos){
    if (count($photos) > 0){
        foreach ($photos->size as $photo){
            $pattern = '/_o\.jpg$/';
            if (preg_match($pattern, $photo->source) > 0){
                system("wget {$photo->source}");
            }
        }
    } else {
        return 'error: no photos found';
    }
    return 'photos found';
}

function run_request($start){
    $total_photos = 2115;
    $yql_url = 'http://query.yahooapis.com/v1/public/yql?';
    $user_id = 'USER_ID';
    $query = "select source from flickr.photos.sizes where photo_id in (select id from flickr.photos.search($start,300) where user_id='$user_id')";
    $query_url = $yql_url . 'q=' . urlencode($query) . '&format=json';
    
    $photos = json_decode(file_get_contents($query_url));
    $result = build_photos($photos->query->results);
    
    $start += 300;
    if ($start < $total_photos){
        run_request($start);
    }
}
?>
