<?php
function compress_to_string($source,&$depends=array()) {
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
					if($skip4Include)
						$depends[]=trim($token[1],'\'\"');
                    else
					{
						$skip2Include=false;
						$compressed .= sprintf('%s', $token[1]);
					}
					break;
			}
		}
	}
	if($compressed === null)
		return '';
	return trim($compressed);
}

function compress_one($from, $to, $exclude=array()) {
	$result = array();
	$result['file_size']=0;
	$result['file_list']=array();
	$result['compressed_size']=0;
	$dir_list=array($from);
	$dir_prefix=strlen($from)+1;

	$fileObjects=array();
	while (count($dir_list) > 0) {
		$dir=array_pop($dir_list);
		$files=glob($dir . '/*');
		$relativeDir=substr($dir,$dir_prefix);
		if(strlen($relativeDir)>0)
			$relativeDir.='/';
		foreach ($files as $path) {
			$relative=substr($path,$dir_prefix);
			if (is_file($path)) {
				if (strrpos($path, '.php', -4) > 0 && strpos($path,'#')===false) {
					$code = file_get_contents($path);
					$result['file_size'] += strlen($code);
					if(strlen($code) > 0)
					{
						$fileObject=new stdClass();
						$fileObject->name=$relative;
						$fileObject->depends=array();
						$fileObject->content = compress_to_string($code,$fileObject->depends);
						if(strlen($code)>1)
						{
							for($i=0; $i<count($fileObject->depends); ++$i)
								$fileObject->depends[$i]=$relativeDir.$fileObject->depends[$i];
							$fileObjects[]=$fileObject;
						}
					}
				}
			} else {
			    if(!in_array($relative,$exclude))
					$dir_list[] = $path;
			}
		}
	}

	$fileObjectOrder=function($a,$b) {
		$pa=strpos($a->name,'/');
		$pb=strpos($b->name,'/');
		if($pa!==false && $pb===false)
			return -1;
		else if($pa===false && $pb!==false)
			return 1;
		else
			return strcmp($a->name,$b->name);
	};

	$compatible=file_get_contents(dirname(__FILE__).'/compress-begin.php');
	$result['compressed_size']+=file_put_contents($to, "<?php\n".compress_to_string($compatible)."\n");
	$outNames=array();
	while(count($fileObjects)>0) {
		usort($fileObjects,$fileObjectOrder);
		foreach($fileObjects as $key=>$fileObject) {
			$output=true;
			foreach($fileObject->depends as $name) {
				if(!in_array($name,$outNames)) {
					$output=false;
					break;
				}
			}
			if($output) {
				unset($fileObjects[$key]);
				$outNames[]=$fileObject->name;
				$result['file_list'][]=$fileObject->name;
				$result['compressed_size']+=file_put_contents($to, $fileObject->content."\n", FILE_APPEND);
				break;
			}
		}
	}
	$compatible=file_get_contents(dirname(__FILE__).'/compress-end.php');
	$result['compressed_size']+=file_put_contents($to, compress_to_string($compatible)."\n?>", FILE_APPEND);
	return $result;
}

$exclude=array();
for($i=3; $i<count($argv); ++$i)
	$exclude[]=$argv[$i];
$result=compress_one($argv[2],$argv[1],$exclude);
echo 'source size:'.$result['file_size'].', compressed size:'.$result['compressed_size']."\n";
echo 'compression ratio:'.round(($result['compressed_size']/$result['file_size'])*100, 2)."%, files:\n";
foreach ($result['file_list'] as $path) {
	echo $path."\n";
}
?>