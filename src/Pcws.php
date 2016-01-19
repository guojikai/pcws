<?php

namespace Pcws;

use ErrorException;

class Pcws {

	/**
	 * word segment
	 * @param String string 待分割的字符串
	 * @return Array 结果数组
	 */
	public static function segment($string = '', $length = 1)
	{
		$string = self::stripPunctuation($string);
		if($string == '') return [];
		$html = self::getHtml('http://5.tbip.sinaapp.com/api.php?str='. urlencode($string) .'&type=str'); //type: str or json
		$array = explode(',', $html);
		$new_array = array_filter($array, function ($word) use ($length) {
				if(preg_match("/^[a-zA-Z\s]+$/", $word)) return true;
				return ( strlen($word) >= $length*3 && trim($word) != '' );
		});
		return join(' ', $new_array);
	}

	//strip all punctuation
	private static function stripPunctuation($text)
	{
		$puns = array(",", ".", "_", "+", "\\", "/", "|", ";", "\"", "!", "?", "%", "^", "(", ")", "?", "-", "=", "<", ">",
									"$", "&", "#", "@", "{", "}", "[", "]", "~", "*", "`", //单字节结束
									"　", "：", "（", "）", "．", "。", "，", "？", "‘", "’", "、", "—", "…", "★", "☆", "◆", "█",
									"◢", "◣", "♀", "※", "□", "◇", "〓", "◥", "◤", "●", "▲", "；", "！", "の", "㊣", //单符号结束
									"『", "』", "「", "」", "【", "】", "〖", "〗", "《", "》", "“", "”", "［", "］",
									);
		foreach($puns as $pun) {
			$text = self::cstr_replace($pun, ' ', $text);
		}
		return trim($text);
	}

	//get api html result
	private static function getHtml($url)
	{
		try {
			$html = file_get_contents($url);
		} catch(ErrorException $e) {
			throw new PcwsException("Pcws Error: Request failed!");
		}
		return $html;
	}

	/**
	 * str_replace for chinese
	 * @param String $needle 做替换的字符
	 * @param String $str 被替换的字符串
	 * @return String 结果字符串
	 */
	private static function cstr_replace($needle, $str, $haystack, $charset = 'utf-8')
	{
		if(strlen($needle) == 0 || strlen($haystack) == 0) return $haystack;
		$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312']  = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk']     = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re['big5']    = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";

		preg_match_all($re[$charset], $haystack, $match_haystack);
		preg_match_all($re[$charset], $needle, $match_needle);

		for($i=0; $i<count($match_needle); $i++) {
			if(!in_array($match_needle[0][$i], $match_haystack[0])) return $haystack; //无匹配
		}

		$match_haystack = $match_haystack[0];
		$match_needle = $match_needle[0];
		for($i=0; $i<count($match_haystack); $i++){
			if($match_haystack[$i] == '') continue;
			if($match_haystack[$i] == $match_needle[0]) {
				if(count($match_needle) == 1) { //如果只一个字符
					$match_haystack[$i] = $str;
				} else {
					$flag = true;
					for($j = 1; $j < count($match_needle); $j ++) {
						if(!isset($match_haystack[$i + $j]) || $match_haystack[$i + $j] != $match_needle[$j]) {
							$flag = false;
							break;
						}

					}
					if($flag) {//匹配
						$match_haystack[$i] = $str;
						for($j=1; $j<count($match_needle); $j++){
							$match_haystack[$i+$j] = '';
						}
					}
				}
			}
		}
		return implode('', $match_haystack);
	}

}

