<?php
function compress_to_string($source) {
	$skip2Include=true;
	$skip4Include=false;
	$needCloseTag=false;
	$compressed = null;
	$tokens = token_get_all($source);
	foreach ($tokens as $token) {
		if (!is_array($token)) {
			if($skip4Include)
				$skip4Include=false;
			else
				$compressed .= $token;
		} else {
			switch ($token[0]) {
				case T_INLINE_HTML:
				case T_COMMENT:
				case T_DOC_COMMENT:
					break;
				case T_WHITESPACE:
				case T_ENCAPSED_AND_WHITESPACE:
					$compressed .= ' ';
					break;
				case T_INCLUDE:
				case T_INCLUDE_ONCE:
				case T_REQUIRE:
				case T_REQUIRE_ONCE:
					if($skip2Include)
						$skip4Include=true;
					else
						$compressed .= $token[1];
					break;
				case T_OPEN_TAG:
					break;
				case T_CLOSE_TAG:
					if($needCloseTag)
					{
						$needCloseTag=false;
						$compressed .= '?>';
					}
					break;
				case T_OPEN_TAG_WITH_ECHO:
					$needCloseTag=true;
					$compressed .= '<?php echo ';
					break;
				default:
					if(!$skip4Include)
					{
						$skip2Include=false;
						$compressed .= sprintf('%s', $token[1]);
					}
					break;
			}
		}
	}
	return trim($compressed);
}

function compress_one($from, $to) {
	$result = array();
	$result['file_size']=0;
	$result['file_list']=array();
	$result['compressed_size']=0;
	$dir_list = array($from);
	file_put_contents($to, "<?php\n");
	$compatible= file_get_contents(dirname(__FILE__).'/compress-begin.php');
	file_put_contents($to, compress_to_string($compatible)."\n", FILE_APPEND);
	while (count($dir_list) > 0) {
		$files = glob(array_pop($dir_list) . '/*');
		foreach ($files as $path) {
			if (is_file($path)) {
				if (strrpos($path, '.php', -4) > 0) {
					$result['file_list'][]=$path;
					$code = file_get_contents($path);
					$result['file_size'] += strlen($code);
					$code = compress_to_string($code)."\n";
					$result['compressed_size'] += strlen($code);
					file_put_contents($to, $code, FILE_APPEND);
				}
			} else {
				$dir_list[] = $path;
			}
		}
	}
	$compatible= file_get_contents(dirname(__FILE__).'/compress-end.php');
	file_put_contents($to, compress_to_string($compatible)."\n?>", FILE_APPEND);
	return $result;
}

$result=compress_one($argv[1],$argv[2]);
echo 'source size:'.$result['file_size'].', compressed size:'.$result['compressed_size']."\n";
echo 'compression ratio:'.round(($result['compressed_size']/$result['file_size'])*100, 2)."%, files:\n";
foreach ($result['file_list'] as $path) {
	echo $path."\n";
}
?>