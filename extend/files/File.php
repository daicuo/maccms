<?php
namespace files;

class File
{
    /**
     * has 判断文件是否存在
     * @param  filename string  文件路径
     * @return  bool 
     */
    public function f_has($filename='')
    {
       $is = is_file($filename);
       return $is ? 1 : 0 ;
    }
	
    /**
     * d_has 判断文件夹是否存在
     * @param  dir string  目录
     * @return  bool 
     */
    public function d_has($dir='')
    {
       $is = is_dir($dir);
       return $is ? 1 : 0 ;
    }
	
    /**
     * d_create 递归创建目录
     * @param   dir string  目录
     * @return  bool 
     */
    public function d_create($dir='')
    {
		if(!$dir || $dir=="." || $dir =="./") return true;
        
        if(!$this->d_has($dir))
		{
			 $is = mkdir($dir, 0777, true); 
		     return $is ? 1 : 0 ;
		}
        
		return 1;
    }
	
    /**
     * write 文件写入
     * @param   filename string  文件路径
     * @param   data string   文件中要写入的内容
     * @return  bool 
     */
    public function write($filename='', $data='')
    {
		$pathinfo = pathinfo($filename);
        
		$dir = $pathinfo['dirname'];
        
		$file = $pathinfo['basename'];
		
        if(!$this->d_has($dir)){
		     mkdir($dir,0777,true); 
		}
		$is = file_put_contents($filename,$data);
		return $is ? 1 : 0 ;
    }
    
    /**
     * write_array 数组保存到文件
     * @param   filename string  文件路径
     * @param   dataArray array  数组
     * @return  bool 
     */
    public function write_array($filename='', $dataArray='')
    {
		if(is_array($dataArray)){
            $con = var_export($dataArray,true);
        } else{
            $con = $dataArray;
        }
        
        $con = "<?php\nreturn $con;\n?>";//\n!defined('IN_MP') && die();\nreturn $con;\n
        
        return $this->write($filename, $con);
    }
	
    /**
     * read 文件读取
     * @param   filename string  文件路径
     * @return  string 
     */
    public function read($filename='')
    {
		if($this->f_has($filename)){
            $content = file_get_contents($filename);
            return $content;		
		}else{
		     return "";
		}
    }
    
    /**
     * read_array 读取文件内容，将读取的内容放入数组中，每个数组元素为文件的一行，内容包括换行
     * @param   filename string  文件路径
     * @return  array 
     */	
	function read_array($filename="")
    {
        if($this->f_has($filename))
		{
             return  file($filename);		
		}
		else
		{
		     return array();
		}
        
    }
	
    /**
     * f_delete 文件删除
     * @param   filename string  文件路径
     * @return  array 
     */	
	function f_delete($filename="")
    {
        if($this->f_has($filename)){
			//chmod($filename , 0777);
			return @unlink($filename);
		}
    }
	
    /**
     * d_delete 文件夹删除
     * @param   filename string  文件路径
     * @return  array 
     */	
	function d_delete($dir="")
    {
        //先删除目录下的文件：
        if(!$this->d_has($dir)) return true;
        
        $dh = opendir($dir);
        
        while ($file=readdir($dh)) {
            if($file!="." && $file!="..") {
                $fullpath = $dir.DIRECTORY_SEPARATOR.$file;
                if(!is_dir($fullpath)) {
                   @unlink($fullpath);
                } else {
                  $this->d_delete($fullpath);
                }
            }
        }
		 
        closedir($dh);
        
        //删除当前文件夹：
        if(rmdir($dir)){
            return true;
        } else {
            return false;
        }
    }
	
	
    /**
     * copy_ 拷贝文件或目录
     * @param string $new 拷贝目录或者文件
     * @param string $old 目标目录或者文件
     * @param string $type 0为删除拷贝目录 1为不删除拷贝目录
     * @return bool
     */	
	function copy_($new,$old,$type=1)
    {
		 $is = false;
		 
	     if(!file_exists($old) && !is_dir($old)) return false;
		 $pathinfo_new = pathinfo($new);
		 $path=isset($pathinfo_new['extension'])?$pathinfo_new['dirname']:$new;
		 if(!is_dir($path))  mkdir($path, 0777, true);

	     if(is_file($old))
		 {
			  if(!isset($pathinfo_new['extension']))
			  {
				  $pathinfo = pathinfo($old);
				  $is = copy($old,$new. DIRECTORY_SEPARATOR . $pathinfo['basename']);
			  }
			  else
			  {
				  $is = copy($old,$new);
			  }
		 }
		 else
		 {
			 if(!isset($pathinfo_new['extension']))
			 {
				  $dir= scandir($old);
				  foreach ($dir as $filename ) 
				  { 
				      if(!in_array($filename,array('.','..')) )
					  {
						   if(is_dir($old.DIRECTORY_SEPARATOR.$filename))
						   {
							   $is = $this->copy_($new.DIRECTORY_SEPARATOR.$filename,$old.DIRECTORY_SEPARATOR.$filename,$type);
							   if(!$is) return false;
							   continue;
						   }
						   else
						   {
							    $is = copy($old.DIRECTORY_SEPARATOR.$filename,$new.DIRECTORY_SEPARATOR.$filename);
						   }
					  }
				  }
				 
			 }
		 }
		 return $is ;  
    }
	
    /*
     * get_all_dir 获取目录下的所有文件路径 包括子目录的文件
     * @param dir string  文件夹路径
     * @return array
     */
	function get_all_dir($dir)
    {
        $result = array();
        $handle = opendir($dir);
        if ( $handle )
        {
            while ( ( $file = readdir ( $handle ) ) !== false )
            {
                if ( $file != '.' && $file != '..')
                {
                    $cur_path = $dir.DIRECTORY_SEPARATOR.$file;
                    if ( is_dir ( $cur_path ) )
                    {
                        $files=$this->get_all_dir( $cur_path );
                        if($files) $result=$result?array_merge($result, $files):$files;
                    }
                    else
                    {
                        $result[] = $cur_path;
                    }
                }
            }
            closedir($handle);
        }
        return $result;
    }
	
    /**
     * is_write 判断文件或者文件夹是否有对象权限
     * @param $file string  文件或者文件夹路径
     * @return int
     */
	function is_write($file)
    {
         $pathinfo = pathinfo($file);
         
         $extension = isset($pathinfo['extension']) ? $pathinfo['extension'] : NULL;

         if($extension){
             return is_writable($file);
         }else{
            if (is_dir($file) || mkdir($file, 0755, true)) {
                return true;
            }
            return false;
         }
    }
}
?>