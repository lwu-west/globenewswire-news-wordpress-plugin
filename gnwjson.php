<?php
/*
  Plugin Name: GlobeNewswire News
  Plugin URI:
  Description: GlobeNewswire News Plugin enables a shortcut to display press releases from GlobeNewswire, one of the world's largest newswire distribution networks.
  Author: GlobeNewswire
  Version: 1.0.0
  Author URI: http://www.globenewswire.com
 */
class GnwNews {

    function __construct() {
        add_shortcode( 'gnw', array( $this, 'gnw_json' ) );
    }


    const GNW_SUMMARY_MAX_LENGTH = 30;
    const GNW_CACHE_KEY_LENGTH = 22;
    const GNW_SHORT_TERM_CACHE_SECONDS = 60;
    const GNW_LONG_TERM_CACHE_SECONDS = 60 * 5;

    /**
     * Shortcode usage:
     * Example: [gnw url="https://rss.globenewswire.com/WpFeed/search/covid-19" article="globenewswire"]
     * where
     *    the url param is the GlobeNewswire JSON feed
     *
     *    the article param accepts one of the three options: "{PAGE_URL}" | inline | globenewswire
     *       "{PAGE_URL}": display article in a separate, dedicated WordPress page (to be created by user)
     *       inline: display article inside the Post/Page/Widget where this shortcode is called
     *       globenewswire: display article on the GlobeNewswire.com web site. (Default)
     *
     *    the show_summary param toggles show/hide of article summary. Default: yes
     *
     *    the headline_separator param sets the text to separate the headlines. Default: <hr class="gnw_headline_separator"/>
     *       For security reasons, please only use plain text, such as "**********" or "-----------". Custom HTML will be HTML-encoded
     *
     * This is the shortcode main function
     * @param array $attributes
     * @global $article
     * @global string $article_display_option
     * @global string $show_summary
     * @global string $headline_separator
     *
     * @return string HTML
     */
    function gnw_json( $attributes ) {
        global $article;
        global $article_display_option;
        global $show_summary;
        global $headline_separator;

        $atts = shortcode_atts( array( 'url' => '#',
                                       'article' => 'globenewswire',
                                       'show_summary' => 'yes',
                                       'headline_separator' => '<hr class="gnw_headline_separator"/>'),
                                $attributes);

        // getting options from shortcode parameters
        $url = $atts['url'];
        $article_display_option = $atts['article'];
        $show_summary = $atts['show_summary'];
        $headline_separator = $atts['headline_separator'];

        switch ( $article_display_option ) {
            case 'inline':
                // if gnw_id=xxxx exists,
                // get individual article by appending to URL /Content/FullText/Identifier/xxxx
                // gnw_id looks like "1234567/language/en"
                $gnw_id = sanitize_text_field($_GET['gnw_id']);
                if( !empty( $gnw_id ) ) {
                    $url .= "/Content/FullText/Identifier/{$gnw_id}";
                    $json = $this->gnw_get( $url );
                    $article = $json[0];
                    return $this->gnw_template_article( $article_display_option );
                };
                // no break here, continue to default to handle list of headlines
            case 'globenewswire':
            default: // page || inline sans gnw_id

                // show list of articles
                $url .= '/Content/briefplain';
                $json = $this->gnw_get( $url );
                return implode ( $headline_separator, array_map( array($this, 'gnw_template'), $json) ); //map reduce array of articles
        }
    }

    /**
     * Display the content of an article
     */
    function gnw_template_article() {

        add_action( 'wp_enqueue_scripts', $this->gnw_css() );

        add_filter('template_include',  load_template(plugin_dir_path( __FILE__ ) . 'gnwjson.template.php' ));

    }

    /**
     * Display a single article within the headlines loop
     * @param article $a
     * @global string $article_display_option
     * @global string $show_summary
     *
     * @return string HTML
     */
    function gnw_template( $a ) {
        global $article_display_option;
        global $show_summary;

        $escaped_title = esc_html( $a->Title );

        $summary_or_content = empty( $a->Summary ) ? $a->Content : $a->Summary;
        $summary = wp_trim_words( wp_strip_all_tags($summary_or_content, true), self::GNW_SUMMARY_MAX_LENGTH, '...');
        $summary_tag = ('yes' === $show_summary) ? '<div class="gnw_wp_summary">'. $summary . '</div>' : '';

        $url = '';

        $date_time_tag = empty( $a->ReleaseDateTime ) ?
            '' :
            '<small class="gnw_wp_datetime">' . $a->ReleaseDateTime . '</small>';

        switch ( $article_display_option ) {
            case 'globenewswire':
                $url = $a->Url;
                break;
            case 'inline':
                $url .= add_query_arg( 'gnw_id', $this->gnw_extract_id($a->Url), get_permalink() );
                break;
            default: //URL of article page
                $url .= add_query_arg( 'gnw_id', $this->gnw_extract_id($a->Url), $article_display_option );
                break;
        }
        return <<<EOD
        <p class="gnw_wp_headlines">
        <div class="gnw_wp_title"><a href="$url">{$escaped_title}</a></div>
        {$date_time_tag}
        {$summary_tag}
        </p>
EOD;
    }

    /**
     * Retrieve via HTTP the body of the given URL
     * @param string $url
     *
     * @return string body
     * Note: Object cache doesn't persist beyond request unless a persistent cache plugin is installed
     */
    function gnw_get( $url ) {
        $cache_key = substr( $url, -1 * self::GNW_CACHE_KEY_LENGTH );
        $body = wp_cache_get( $cache_key, 'gnw' );

        if ( false === $body ) {
            $response = wp_safe_remote_get( $url );
            if( is_wp_error( $response ) ) {
                return $this->gnw_get_error_handling( 'Error: getting ' . $url, $cache_key ) ;
            }

            $body = wp_remote_retrieve_body( $response );

            if( empty( $body ) || strpos( $body, '{"Success":false,') !== false ) {
                return $this->gnw_get_error_handling( 'Empty or invalid body', $cache_key );
            }
            $body = json_decode( $body );

            // cache for 60 seconds
            wp_cache_set( $cache_key, $body, 'gnw', self::GNW_SHORT_TERM_CACHE_SECONDS );

            // The long term cache is used in case we have connection problem
            // with the feed
            wp_cache_set( $cache_key, $body, 'gnwlongterm', self::GNW_LONG_TERM_CACHE_SECONDS );
        }
        return $body;
    }

    /**
	 * Parse GlobeNewswire article URL and retrieve the gnw_id
	 * @param string $url
	 * 
	 * @return string gnw_id, e.g. "1234567/language/en"
	 */
	function gnw_extract_id( $url ) {
        // extract GlobeNewswire article id and language code
	    $pattern = "/\/news-release\/\d+\/\d+\/\d+\/(\d+)\/\d+\/([a-z][a-z])/";
        if( preg_match( $pattern, $url, $matches ) ) {
            return $matches[1] . '/language/'. $matches[2];
        }
        else {
            return "";
        }
    }

    function gnw_css() {
	    wp_enqueue_style( 'gnwcss', plugins_url( 'gnwjson.css', __FILE__ ) );
    }

	/**
	 * Log error and
	 * if there exists a long-term cache, return it
	 * @param string $err
	 * @param string $cache_key
	 * 
	 * @return JSON []|long-term cache value
	 */
	function gnw_get_error_handling( $err, $cache_key ) {
    	error_log( $err);

    	// if we have a valid long-term cache, use its content instead
    	$body_longterm = wp_cache_get( $cache_key, 'gnwlongterm');
    	if( false === $body_longterm) {
		    return json_decode( '[]' );
	    }
    	else {
    		return $body_longterm;
	    }
    }

}

$g = new GnwNews();