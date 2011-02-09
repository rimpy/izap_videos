<?php
/**
 * iZAP izap_videos
 *
 * @package Elgg videotizer, by iZAP Web Solutions.
 * @license GNU Public License version 3
 * @Contact iZAP Team "<support@izap.in>"
 * @Founder Tarun Jangra "<tarun@izap.in>"
 * @link http://www.izap.in/
 * 
 */

if(!is_callable('elgg_add_action_tokens_to_url')) {
  /**
   * Adds action tokens to URL
   *
   * @param str $link Full action URL
   * @return str URL with action tokens
   * @since 1.7
   */
  function elgg_add_action_tokens_to_url($url) {
    $components = parse_url($url);

    if (isset($components['query'])) {
      $query = elgg_parse_str($components['query']);
    } else {
      $query = array();
    }

    if (isset($query['__elgg_ts']) && isset($query['__elgg_token'])) {
      return $url;
    }

    // append action tokens to the existing query
    $query['__elgg_ts'] = time();
    $query['__elgg_token'] = generate_action_token($query['__elgg_ts']);
    $components['query'] = http_build_query($query);

    // rebuild the full url
    return elgg_http_build_url($components);
  }
}

if(!is_callable('elgg_http_build_url')) {
  /**
   * Rebuilds a parsed (partial) URL
   *
   * @param array $parts Associative array of URL components like parse_url() returns
   * @return str Full URL
   * @since 1.7
   */
  function elgg_http_build_url(array $parts) {
    // build only what's given to us.
    $scheme = isset($parts['scheme']) ? "{$parts['scheme']}://" : '';
    $host = isset($parts['host']) ? "{$parts['host']}" : '';
    $port = isset($parts['port']) ? ":{$parts['port']}" : '';
    $path = isset($parts['path']) ? "{$parts['path']}" : '';
    $query = isset($parts['query']) ? "?{$parts['query']}" : '';

    $string = $scheme . $host . $port . $path . $query;

    return $string;
  }
}

if(!is_callable('elgg_parse_str')) {
  /**
   * Parses a string using mb_parse_str() if available.
   * NOTE: This differs from parse_str() by returning the results
   * instead of placing them in the local scope!
   *
   * @param str $str
   * @return array
   */
  function elgg_parse_str($str) {
    if (is_callable('mb_parse_str')) {
      mb_parse_str($str, $results);
    } else {
      parse_str($str, $results);
    }

    return $results;
  }
}

if(!is_callable('unregister_plugin_hook')) {
  /**
   * Unregister a function to a plugin hook for a particular entity type
   *
   * @param string $hook The name of the hook
   * @param string $entity_type The name of the type of entity (eg "user", "object" etc)
   * @param string $function The name of a valid function to be run
   */
  function unregister_plugin_hook($hook, $entity_type, $function) {
    global $CONFIG;
    foreach($CONFIG->hooks[$hook][$entity_type] as $key => $hook_function) {
      if ($hook_function == $function) {
        unset($CONFIG->hooks[$hook][$entity_type][$key]);
      }
    }
  }
}

if(!is_callable('get_entities_from_metadata_multi')) {
  
}