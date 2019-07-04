<?php

namespace App\Http\Controllers;

use DOMDocument;
use Illuminate\Http\Request;

class HtmlConveterController extends Controller {

	/**
	 * Map
	 *
	 * @param Request $request
	 */
	public function index($code) {

		$html = self::getCode($code);
		if ($html) {
			echo $html;
			// echo 'process';
		}

	}
	public function getCode($code) {
		try {
			$url = 'https://www.obd-codes.com/' . $code;

			// $html = file_get_contents($url, false, $context);

			if (self::get_http_response_code($url) != "200") {
				return false;
			} else {
				$html = file_get_contents($url);
			}

			$dom = new DOMDocument();
			libxml_use_internal_errors(true);
			$dom->loadHTML($html);

			foreach (iterator_to_array($dom->getElementsByTagName('script')) as $item) {
				$item->parentNode->removeChild($item);
			}
			foreach (iterator_to_array($dom->getElementsByTagName('ins')) as $item) {
				$item->parentNode->removeChild($item);
			}
			foreach (iterator_to_array($dom->getElementsByTagName('a')) as $item) {
				// $item->parentNode->removeChild($item);
				$href = $item->setAttribute('href', '');
				// dd($href);

			}

			$html = $dom->saveHTML();
			$main = strpos($html, '<div class="main">');
			// dd($discussions);
			$html = substr($html, $main);
			$discussions = strpos($html, '<h2>Related DTC Discussions</h2>');

			$lastpart = substr($html, $discussions);
			$html = str_replace($lastpart, '', $html);

			return $html;
		} catch (Exception $e) {
			return false;
		}
	}

	public function get_http_response_code($url) {
		$headers = get_headers($url);
		return substr($headers[0], 9, 3);
	}

}
