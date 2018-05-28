<?php

class CRM_Actiontracker_HtmlClickTracker {

  /**
   * Based on filterContent from Flexmailer.
   */
  public static function addTrackingCode($msg, $mailing_id, $queue_id) {
    return self::replaceHrefUrls($msg,
      function ($url) use ($mailing_id, $queue_id) {
        if (preg_match('/&amp;cs=[_0-9A-Za-z]+/', $url, $matches)) {
          $t = preg_replace('/&amp;cs=[_0-9A-Za-z]+/', '', $url);

          $data = \CRM_Mailing_BAO_TrackableURL::getTrackerURL($t, $mailing_id, $queue_id);
          $data = htmlentities($data, ENT_NOQUOTES);

          $data = preg_replace('/.*url.php\?/', '', $data);
          $url .= '&amp;' . $data;

          return $url;
        }

        return $url;
      }
    );
  }

  /**
   * Find any HREF-style URLs and replace them.
   *
   * @param string $html
   * @param callable $replace
   *   Function(string $oldHtmlUrl) => string $newHtmlUrl.
   * @return mixed
   *   String, HTML.
   */
  public static function replaceHrefUrls($html, $replace) {
    $callback = function ($matches) use ($replace) {
      return $matches[1] . $replace($matches[2]) . $matches[3];
    };

    // Find anything like href="..." or href='...' inside a tag.
    $tmp = preg_replace_callback(
      ';(\<[^>]*href *= *")([^">]+)(");', $callback, $html);
    return preg_replace_callback(
      ';(\<[^>]*href *= *\')([^">]+)(\');', $callback, $tmp);
  }

}
