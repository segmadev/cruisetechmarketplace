<?php 
class instagram {
    function scrapeUser($username) {
        $url = "https://i.instagram.com/api/v1/users/web_profile_info/?username=" . urlencode($username);
        
        $headers = [
            "x-ig-app-id: 936619743392459",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36",
            "Accept-Language: en-US,en;q=0.9,ru;q=0.8",
            "Accept-Encoding: gzip, deflate, br", // Accept compressed response
            "Accept: */*",
        ];
    
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $result = curl_exec($ch);
        curl_close($ch);
    
        // Check if the response is compressed and decompress it
        // if ($result && substr($result, 0, 2) === "\x1f\x8b") { // Check for gzip header
            $result = gzdecode($result);
        // }
        
        $data = json_decode($result, true);
        return $data['data']['user'] ?? null; // Return user data or null if not found
    }

    function extractUsername($input) {
        // Check if the input contains 'instagram.com'
        if (strpos($input, 'instagram.com') !== false) {
            // Parse the URL
            $parsedUrl = parse_url($input);
            
            // Check if the host contains 'instagram.com'
            if (isset($parsedUrl['host']) && strpos($parsedUrl['host'], 'instagram.com') !== false) {
                // Remove slashes and split the path to get the username
                $pathParts = explode('/', trim($parsedUrl['path'], '/'));
                
                // Return the first part of the path as the username
                return $pathParts[0];
            }
        } else {
            // If 'instagram.com' is not found, assume the input is already a username
            return $input;
        }
    
        return null; // Return null if the input is not a valid Instagram link or username
    }    
}