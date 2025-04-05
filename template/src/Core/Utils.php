<?php
namespace {{PLUGIN_NAMESPACE}}\Core;

class Utils {
    private static $start_time;
    private static $start_memory;

    public static function fetch_data($url = null) {
        if (!is_null($url)) {
            // Init CURL
            $handler = curl_init();
            // CURL options
            curl_setopt($handler, CURLOPT_URL, $url);
            curl_setopt($handler, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($handler, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($handler, CURLOPT_TIMEOUT, 10); // Timeout after 10 seconds
            curl_setopt($handler, CURLOPT_FAILONERROR, true); // Fail on HTTP errors
            // Load data & close connection
            $data = curl_exec($handler);
            if (curl_errno($handler)) {
                error_log("cURL error: " . curl_error($handler));
                curl_close($handler);
                return false;
            }
            curl_close($handler);
            return $data;
        }
        return false;
    }    

    public static function start_performance_measurement() {
        self::$start_time = microtime(true);
        self::$start_memory = memory_get_usage();
    }

    public static function end_performance_measurement() {
        $end_time = microtime(true);
        $end_memory = memory_get_usage();
        $execution_time = $end_time - self::$start_time;
        $peak_memory_usage = memory_get_peak_usage(true);
        $memory_used = $end_memory - self::$start_memory;

        return [
            'Execution Time' => number_format($execution_time, 4) . ' seconds',
            'Peak Memory Usage' => self::format_bytes($peak_memory_usage),
            'Memory Used' => self::format_bytes($memory_used)
        ];
    }    

    private static function format_bytes($bytes) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $unit = 0;

        while ($bytes >= 1024 && $unit < count($units) - 1) {
            $bytes /= 1024;
            $unit++;
        }

        return number_format($bytes, 2) . ' ' . $units[$unit];
    }    

    public static function check_user_role($roles) {
        if (!is_array($roles)) $roles = [$roles];

        $current_user = wp_get_current_user();
        if (!($current_user instanceof \WP_User)) {
            wp_send_json(array(
                'error' => 'Sorry, you are not allowed to do that.',
            ), 401);
            return false;
        }

        if ( array_intersect( $roles, $current_user->roles ) ) {
            // Current user has the given role
            return true;
        } else {
            wp_send_json(array(
                'error' => 'Sorry, you are not allowed to do that.',
            ), 401);
            return false;
        }
    }

    public static function sanitize_input( $input ) {
        return wp_strip_all_tags( stripslashes( $input ) );
    }

    public static function generate_random_string( $length = 10 ) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function get_option( $option_name, $default = '' ) {
        $option = get_option( $option_name, $default );
        if ( false === $option ) {
            return $default;
        }
        return $option;
    }
            
    public static function format_date( $date ) {
        $format = get_option( 'date_format' );
        return date_i18n( $format, strtotime( $date ) );
    }

    public static function redirect( $url ) {
        wp_redirect( $url );
        exit;
    }

    public static function is_valid_email( $email ) {
        $email = sanitize_email( $email );
        return is_email( $email );
    }

    public static function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if(isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } else if(isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if(isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } else if(isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN';
        }
        return sanitize_text_field( $ipaddress );
    }

    public static function sanitize_array( $array ) {
        foreach ( $array as $key => &$value ) {
            if ( is_array( $value ) ) {
                $value = self::sanitize_array( $value );
            } else {
                $value = sanitize_text_field( $value );
            }
        }
        return $array;
    }
            
    public static function limit_char($word, $char = '_', $max = 3) {
        // Use regex to replace sequences of the character with the character itself
        $word = preg_replace('/' . preg_quote($char, '/') . '{2,}/', $char, $word);
    
        // Split the word by the character, limiting to $max + 1 parts
        $parts = explode($char, $word, $max + 1);
    
        // If there are more parts than the max allowed, remove the excess character from the last part
        if (count($parts) > $max) {
            $parts[$max] = str_replace($char, '', $parts[$max]);
        }
    
        // Join the parts back together with the character
        return implode($char, $parts);
    }

    public static function get_image_extension($url) {
        // Parse the URL to get the path part
        $path = parse_url($url, PHP_URL_PATH);
    
        // Use pathinfo() to get information about the file, including extension
        $file_info = pathinfo($path);
    
        // Check if 'extension' exists in the file info and return it
        if (isset($file_info['extension'])) {
            return 'image/' .  strtolower($file_info['extension']); // Return in lowercase
        }
    
        // Return null if no extension is found
        return null;
    }

    // Helper function to format file size
    public static function format_file_size($size_in_bytes) {
        if ($size_in_bytes >= 1073741824) {
            return number_format($size_in_bytes / 1073741824, 2) . ' GB';
        } elseif ($size_in_bytes >= 1048576) {
            return number_format($size_in_bytes / 1048576, 2) . ' MB';
        } elseif ($size_in_bytes >= 1024) {
            return number_format($size_in_bytes / 1024, 2) . ' KB';
        } else {
            return $size_in_bytes . ' bytes';
        }
    }  

    public static function country_to_language($country_code) {
        // Define a comprehensive mapping of country codes to language codes
        $country_language_map = [
            'af' => 'af_ZA',   // Afrikaans (South Africa)
            'al' => 'sq_AL',   // Albanian (Albania)
            'dz' => 'ar_DZ',   // Arabic (Algeria)
            'ar' => 'es_AR',   // Spanish (Argentina)
            'am' => 'hy_AM',   // Armenian (Armenia)
            'au' => 'en_AU',   // English (Australia)
            'at' => 'de_AT',   // German (Austria)
            'az' => 'az_AZ',   // Azerbaijani (Azerbaijan)
            'bd' => 'bn_BD',   // Bengali (Bangladesh)
            'be' => 'nl_BE',   // Dutch (Belgium)
            'br' => 'pt_BR',   // Portuguese (Brazil)
            'bg' => 'bg_BG',   // Bulgarian (Bulgaria)
            'ca' => 'en_CA',   // English (Canada)
            'cn' => 'zh_CN',   // Chinese (Simplified)
            'tw' => 'zh_TW',   // Chinese (Traditional) (Taiwan)
            'cl' => 'es_CL',   // Spanish (Chile)
            'co' => 'es_CO',   // Spanish (Colombia)
            'hr' => 'hr_HR',   // Croatian (Croatia)
            'cz' => 'cs_CZ',   // Czech (Czech Republic)
            'dk' => 'da_DK',   // Danish (Denmark)
            'eg' => 'ar_EG',   // Arabic (Egypt)
            'fi' => 'fi_FI',   // Finnish (Finland)
            'fr' => 'fr_FR',   // French (France)
            'de' => 'de_DE',   // German (Germany)
            'gr' => 'el_GR',   // Greek (Greece)
            'hk' => 'zh_HK',   // Chinese (Traditional) (Hong Kong)
            'hu' => 'hu_HU',   // Hungarian (Hungary)
            'in' => 'hi_IN',   // Hindi (India)
            'id' => 'id_ID',   // Indonesian (Indonesia)
            'ie' => 'en_IE',   // English (Ireland)
            'il' => 'he_IL',   // Hebrew (Israel)
            'it' => 'it_IT',   // Italian (Italy)
            'jp' => 'ja_JP',   // Japanese (Japan)
            'kr' => 'ko_KR',   // Korean (South Korea)
            'my' => 'ms_MY',   // Malay (Malaysia)
            'mx' => 'es_MX',   // Spanish (Mexico)
            'nl' => 'nl_NL',   // Dutch (Netherlands)
            'nz' => 'en_NZ',   // English (New Zealand)
            'no' => 'nb_NO',   // Norwegian (Norway)
            'pk' => 'ur_PK',   // Urdu (Pakistan)
            'pl' => 'pl_PL',   // Polish (Poland)
            'pt' => 'pt_PT',   // Portuguese (Portugal)
            'ro' => 'ro_RO',   // Romanian (Romania)
            'ru' => 'ru_RU',   // Russian (Russia)
            'sa' => 'ar_SA',   // Arabic (Saudi Arabia)
            'sg' => 'en_SG',   // English (Singapore)
            'za' => 'en_ZA',   // English (South Africa)
            'es' => 'es_ES',   // Spanish (Spain)
            'se' => 'sv_SE',   // Swedish (Sweden)
            'ch' => 'de_CH',   // German (Switzerland)
            'th' => 'th_TH',   // Thai (Thailand)
            'tr' => 'tr_TR',   // Turkish (Turkey)
            'ua' => 'uk_UA',   // Ukrainian (Ukraine)
            'ae' => 'ar_AE',   // Arabic (United Arab Emirates)
            'gb' => 'en_GB',   // English (United Kingdom)
            'us' => 'en_US',   // English (United States)
            'vn' => 'vi_VN',   // Vietnamese (Vietnam)
        ];
    
        // Convert country code to lowercase and return the corresponding language code
        $country_code = strtolower($country_code);
    
        // Return the mapped language code or a default (e.g., en_US) if no match is found
        return isset($country_language_map[$country_code]) ? $country_language_map[$country_code] : 'en_US';
    }

    public static function convertTimestampToDate($timestamp) {
        // Convert milliseconds to seconds by dividing by 1000
        $seconds = $timestamp / 1000;
    
        // Create a DateTime object from the timestamp
        $date = new \DateTime();
        $date->setTimestamp($seconds);
    
        // Return the formatted date (e.g., Y-m-d H:i:s)
        return $date->format('d F Y');
    }
    
    public static function trim_without_cutting_words($text, $max_length) {
        if (strlen($text) <= $max_length) {
            return $text;
        }
    
        // Trim to max length
        $trimmed_text = substr($text, 0, $max_length);
    
        // Find the last space position within the trimmed text
        $last_space = strrpos($trimmed_text, ' ');
    
        if ($last_space !== false) {
            return substr($trimmed_text, 0, $last_space) . '.';
        }
    
        return $trimmed_text; // Fallback in case there's no space
    }

	public function get_thumbnail_with_fallback( $post_id, $size = 'thumbnail' ) {
		if ( has_post_thumbnail( $post_id ) ) {
			$thumbnail_id     = get_post_thumbnail_id( $post_id );
			$image            = wp_get_attachment_image_src( $thumbnail_id, $size );
			$image['caption'] = get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true );

			return $image;
		}

		$content_post = get_post($post_id);
		
		$content = $content_post->post_content;

		// juz_debug($content);
		// die();

		preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches );

		// juz_debug($post_id);
		// juz_debug($matches);

		$matches = array_filter( $matches );
		if ( ! empty( $matches ) ) {
			return [ $matches[1][0], 200, 200 ];
		}

		// $fb_image = get_post_meta( 'facebook_image_id', $post_id );
		// $fb_image = get_post_meta( 'facebook_image_id', $post_id );
		$fb_image = get_post_meta( 'facebook_image_id', $post_id );
		$tw_image = get_post_meta( 'twitter_image_id', $post_id, get_option( 'titles.open_graph_image_id' ) );
		$og_image = $fb_image ? $fb_image : $tw_image;

		if ( ! $og_image ) {
			return false;
		}

		$image            = wp_get_attachment_image_src( $og_image, $size );
		$image['caption'] = get_post_meta( $og_image, '_wp_attachment_image_alt', true );

		// juz_debug($image);
		return $image;
	}   

}
