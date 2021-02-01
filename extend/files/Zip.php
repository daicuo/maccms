<?php
namespace files;

class Zip 
{
    /**
     * 初始化函数
     * @return  bool 
     */
    public function __construct() {
        $this->zip = new \ZipArchive();
        $this->F = new \files\File();
    }

    /**
     * add_file 压缩单个文件
     * @param $filename string  被压缩的文件
     * @param filename_zip string 在压缩文件中的文件名称，如果不设置为压缩文件的名称，默认不设置
     * @return  int     1 or 0 
     */
    public function add_file($filename,$filename_zip=NULL) {
        if($filename_zip)
        {
             $is = $this->zip ->addFile($filename,$filename_zip);  
        }
        else
        {
             $is = $this->zip ->addFile($filename); 
        }

        return $is ? 1 : 0;
    }   

    /**
     * zip 压缩整个目录
     * @param $dir string  压缩目录路径
     * @param zipname string 压缩文件的文件名，包括路径
     * @return  int     1 or 0 
     */
    public function zip($dir,$zipfilename="zip.zip") {
        $pathinfo = pathinfo($zipfilename);

        if($this->F->d_create($pathinfo['dirname']) )
        {
              if($this -> zip ->open($zipfilename, \ZipArchive::CREATE) === TRUE){ ///ZipArchive::OVERWRITE 如果文件存在则覆盖
                     $this -> createZip($dir);
              }
              return $this->zip->close();
        } 
    }
	
    /**
     * unzip 解压缩文件
     * @param zipfilename string  解压缩的文件
     * @param path string 解压缩后的路径
     * @return  int     1 or 0 
     */
    public function unzip($zipfilename="zip.zip",$path="") 
    {
        if ($this->zip->open($zipfilename) === true) {
          $file_tmp = @fopen($zipfilename, "rb");
          $bin = fread($file_tmp, 15); //只读15字节 各个不同文件类型，头信息不一样。
          fclose($file_tmp);
          /* 只针对zip的压缩包进行处理 */
          if (true === $this->getTypeList($bin)){
              $result = $this->zip->extractTo($path);
              $this->zip->close();
              return $result ? 1 : 0;
          } else {
            return 0;
          }
        }
        return 0;
    } 
	
    /**
     * add_file 添加目录到zip对象
     * @param $dir string  压缩目录路径
     * @param zipname string 压缩文件的文件名，包括路径
     * @return  int     1 or 0 
     */
    public function createZip($dir,$parent=NULL) {
        $handle = opendir($dir);
        if ( $handle )
        {
            try{
                while ( ( $file = readdir ( $handle ) ) !== false )
                {
                    if ( $file != '.' && $file != '..')
                    {
                        $cur_path = $dir.DIRECTORY_SEPARATOR.$file;
                        if ( is_dir ( $cur_path ) )
                        {
                            $parentParam = $parent ? $parent."/".$file : $file;
                            $this->createZip($cur_path,$parentParam);
                        }
                        else
                        {
                            $filename_zip = $parent ? $parent."/".$file : $file;
                            $this->add_file($cur_path,$filename_zip);
                        }
                    }
                }
                closedir($handle);
                return 1;
            }catch(\Exception $e){
                   return 0;
             }
        }
    } 
	
    /**
     * get_list 获取压缩文件的列表
     * @param $zipfilename string  压缩文件
     * @param zipname string 压缩文件的文件名，包括路径
     * @return  int     1 or 0 
     */
    public function get_list($zipfilename="zip.zip") {
		    $file_dir_list = array();
			$file_list = array();
			if ($this->zip->open($zipfilename) == true) {
				
				  for ($i = 0; $i < $this->zip->numFiles; $i++) {
						$numfiles = $this->zip->getNameIndex($i);
						if (preg_match('/\/$/i', $numfiles))
						{
						    $file_dir_list[] = $numfiles;
						}
						else
						{
						    $file_list[] = $numfiles;
						}
				  }
			  
			}
			return array('files'=>$file_list, 'dirs'=>$file_dir_list);

    }  
	
    /**
     * getTypeList 得到文件头与文件类型映射表
     * @param $bin string 文件的二进制前一段字符
     * @return  bool
     */
    public function getTypeList($bin) {
      /*$array = array(
            array("504B0304", "zip")
        );
        foreach ($array as $v)
        {
              $blen = strlen(pack("H*", $v[0])); //得到文件头标记字节数
              $tbin = substr($bin, 0, intval($blen)); ///需要比较文件头长度
              if(strtolower($v[0]) == array_shift(unpack("H*", $tbin)))
              {
                   return true;
              }
        }*/
        return true;
    } 
}
?>