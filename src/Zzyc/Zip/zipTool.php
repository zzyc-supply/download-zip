<?php
/**
 * Created by PhpStorm.
 * User: huidaoli
 * Date: 2022/04/28
 * Time: 13:16
 */

namespace Zzyc\Zip;

class zipTool{
    //路径
    protected  $path;
    //要保存的图片类型
    protected  $type;

    /**
     * imageTool constructor.
     * @param string $path
     * @param string $type (gif,png,jpeg,wbmp) 要输出的图片格式
     *
     */
    function  __construct($path ='./',$type='png')
    {
        $this->path = $path;
        $this->type = $type;
    }

    /////////////////////////////////////////////////////添加图片水印/////////////////////////////////////////////////////

    /**
     * @param string $image 原图
     * @param string $waterImg 需要添加的水印图片
     * @param int $position 要添加的位置，默认是0，有10个位置可选（0，1，2，3，4，5，6，7，8，9）
     * @param int $tmd 透明度 默认100
     * @param string $prefix 增加完水印的图片的前缀
     * @param int $is_save 是否保存图片（0-》保存，1-》浏览器输出）
     */
    function addWater($image="",$waterImg="",$position=0,$tmd=100,$prefix='water_',$is_save=0){
        //判断这2个图片是否存在
        if((!file_exists($image)) || (!file_exists($waterImg))){
            return "图片不存在";
           exit();
        }
        //得到原图片和水印图的高度和宽度
        $imageInfo = self::getImageInfo($image);
        $waterImgInfo = self::getImageInfo($waterImg);
        //判断水印图片能否贴上来
        if(!($this->checkImage($imageInfo,$waterImgInfo))){
           return "水印图片过大";
           exit();
        }
        //打开图片
        $imageRes = $this->openAnyImg($image);
        $imageWaterRes = $this->openAnyImg($waterImg);
        //根据水印图片的位置计算水印图片的坐标
        $arrPos = $this->getPosition($position,$imageInfo,$waterImgInfo);
        //贴上水印图片
        imagecopymerge($imageRes,$imageWaterRes,$arrPos['x'],$arrPos['y'],0,0,$waterImgInfo['width'],$waterImgInfo['height'],$tmd);
        //得到要保存图片的文件名
        $newName = $this->createNewName($image,$prefix);
        //得到保存图片的路劲
        $newPath = rtrim($this->path,'/').'/'.$newName;
        //保存图片
        if($is_save==0){
            $this->saveImg($imageRes,$newPath);
        }else{
            $this->showImg($imageRes);
        }
        //销毁资源
        imagedestroy($imageRes);
        imagedestroy($imageWaterRes);
        if($is_save==0){
            return $newPath;
        }
    }

    /**
     * @param $imagePath 图片的路劲
     *  获得图片的信息，宽度、高度、mime
     */
    static  function  getImageInfo($imagePath){
        $info = getimagesize($imagePath);
        $data['width'] = $info[0];
        $data['height'] = $info[1];
        $data['mime'] = $info['mime'];
        return $data;
    }

    /**
     * @param $imageInfo 原图片的信息
     * @param $waterImgInfo 水印图片的信息
     *  检测水印图片是否比原图大
     */
     protected function checkImage($imageInfo,$waterImgInfo){
        if(($waterImgInfo['width']>$imageInfo['width']) || ($waterImgInfo['height']>$imageInfo['height'])){
            return false;
        }
        return true;
    }

    /**
     * @param $imagePath
     * @return resource
     * 根据图片类型选择打开图片的方法
     *
     */
    protected  function  openAnyImg($imagePath){
         //得到图像的mime类型
        $mime = self::getImageInfo($imagePath)['mime'];
        //根据不同的mime类型来使用不同的函数打开图像
        switch ($mime){
            case 'image/jpeg':
                $image = imagecreatefromjpeg($imagePath);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($imagePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($imagePath);
                break;
            case 'image/wbmp':
                $image = imagecreatefromwbmp($imagePath);
                break;
        }
        return $image;
    }

    /**
     * @param $position  要添加的位置，默认是0，有10个位置可选（0，1，2，3，4，5，6，7，8，9）
     * @param $imageInfo 原图的信息
     * @param $waterImgInfo 水印图片的信息
     *  根据位置计算水印图片的坐标 x,y
     */
    protected  function  getPosition($position = 0,$imageInfo,$waterImgInfo){
        switch ($position){
            case 1:
                $x = 0;
                $y = 0;
                break;
            case 2:
                $x = ($imageInfo['width'] - $waterImgInfo['width'])/2;
                $y = 0;
                break;
            case 3:
                $x = $imageInfo['width'] - $waterImgInfo['width'];
                $y = 0;
                break;
            case 4;
                $x = 0;
                $y = ($imageInfo['height'] - $waterImgInfo['height'])/2;
                break;
            case 5:
                $x = ($imageInfo['width'] - $waterImgInfo['width'])/2;
                $y = ($imageInfo['height'] - $waterImgInfo['height'])/2;
                break;
            case 6:
                $x = $imageInfo['width'] - $waterImgInfo['width'];
                $y = ($imageInfo['height'] - $waterImgInfo['height'])/2;
                break;
            case 7:
                $x = 0;
                $y = $imageInfo['height'] - $waterImgInfo['height'];
                break;
            case 8:
                $x = ($imageInfo['width'] - $waterImgInfo['width'])/2;
                $y = $imageInfo['height'] - $waterImgInfo['height'];
                break;
            case 9:
                $x = $imageInfo['width'] - $waterImgInfo['width'];
                $y = $imageInfo['height'] - $waterImgInfo['height'];
                break;
             case 0;
                $x = mt_rand(0,$imageInfo['width'] - $waterImgInfo['width']);
                $y = mt_rand(0,$imageInfo['height'] - $waterImgInfo['height']);
                break;
        }
        return ['x'=> $x,'y'=> $y];
    }


    /**
     * @param $imagePath
     * @param $prefix
     *  组成图片的文件名
     */
    protected  function  createNewName($imagePath,$prefix){
        return $prefix.MD5(time()).'.'.$this->type;
    }

    /**
     * @param $imageRes 要保存的图片资源
     * @param $imagePath 要保存的图片路径
     * 保存图片到指定路径
     */
    protected  function  saveImg($imageRes,$imagePath){
        if(!empty($imageRes) && !empty($imagePath)){
            $func = "image".$this->type;
            $func($imageRes,$imagePath);
        }
    }

    /**
     * @param $imageRes
     * @param $imagePath
     *  直接在浏览器输出图片
     */
    protected  function  showImg($imageRes){
        if($this->type=='png'){
            header("content-type:image/png");
        }elseif ($this->type=='gif'){
            header("content-type:image/gif");
        }elseif ($this->type=='jpeg'){
            header("content-type:image/jpeg");
        }elseif ($this->type=='wbmp'){
            header("content-type:image/wbmp");
        }
        if(!empty($imageRes)){
            $func = "image".$this->type;
            $func($imageRes);
        }
    }

    /////////////////////////////////////////////////////添加文字水印/////////////////////////////////////////////////////
    /**
     * 为图像添加文字标记
     *
     * @param $image 图片
     * @param $content 文本内容
     * @param $size 字体大小
     * @param $font 字体样式 ttf文件
     * @param $x x坐标位置
     * @param $y y坐标位置
     * @param $is_save 是否保存图片（0-》保存，1-》浏览器输出）
     * @return $this
     * modify by qc  2019/5/28
     */
    public function addTextmark($image,$content, $size, $font,$x=30,$y=110,$is_save=0)
    {
        $imageRes = $this->openAnyImg($image);
        $color = imagecolorallocatealpha($imageRes, 248, 248, 255, 0);
        //$X = imagesx($image) - strlen($content) * $size / 2;
        //$Y = imagesy($image) - $size / 1.5;
        imagettftext($imageRes, $size, 0, $x, $y, $color, $font, $content);
        //得到要保存图片的文件名
        $newName = $this->createNewName($image,'text_');
        //得到保存图片的路劲
        $newPath = rtrim($this->path,'/').'/'.$newName;
        if ($is_save==0) {
            $this->saveImg($imageRes,$newPath);
            return $newPath;
        }else{
            $this->showImg($imageRes);
        }
        //销毁资源
        imagedestroy($imageRes);

    }

    /////////////////////////////////////////////////////压缩图片/////////////////////////////////////////////////////

    /**
     * @param $image
     * @param $quality 范围从 0（最差质量，文件最小）到 100（最佳质量，文件最大）
     * @param  $is_save 是否保存到指定路径
     *  图片压缩
     */
    public function compressImg($image,$quality,$is_save){
        $imageRes = $this->openAnyImg($image);
        //得到要保存图片的文件名
        $newName = $this->createNewName($image,'compress_');
        //得到保存图片的路劲
        $newPath = rtrim($this->path,'/').'/'.$newName;
        if($is_save == 1){
            header("content-type:image/jpeg");
            imagejpeg($imageRes,$quality);
            return $newPath;
        }else{
            imagejpeg($imageRes,$newPath,$quality);
            return $newPath;
        }
    }

    public function compressImg1($image,$quality){
        $imageRes = $this->openAnyImg($image);
        imagejpeg($imageRes,$image,$quality);
        return $image;
    }

    /////////////////////////////////////////////////////远程下载图片/////////////////////////////////////////////////////

    /**
     * 远程图片下载工具
     * @param $url 远程文件url
     * @param $save_dir 要保存到本地的目录
     * @param $filename 文件名称
     * @param $type 获取远程文件所采用的方法 0为curl
     */
    public function downLoadImg($url,$save_dir='',$filename='',$type=0){
        if(trim($url)==''){
            return array('file_name'=>'','save_path'=>'','error'=>1);
        }
        if(trim($save_dir)==''){
            $save_dir='./商品数据包';
        }
        if(trim($filename)==''){//保存文件名
            $ext=strrchr($url,'.');
            if($ext!='.gif'&&$ext!='.jpg'){
                return array('file_name'=>'','save_path'=>'','error'=>3);
            }
            $filename=time().$ext;
        }
        if(0!==strrpos($save_dir,'/')){
            $save_dir.='/';
        }
        //创建保存目录
        if(!file_exists($save_dir)&&!mkdir($save_dir,0777,true)){
            return array('file_name'=>'','save_path'=>'','error'=>5);
        }
        //获取远程文件所采用的方法
        if($type){
            $ch=curl_init();
            $timeout=300;
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
            $img=curl_exec($ch);
            curl_close($ch);
        }else{
            ob_start();
            readfile($url);
            $img=ob_get_contents();
            ob_end_clean();
        }
        //$size=strlen($img);
        //文件大小
        $fp2=@fopen($save_dir.$filename,'a');
        fwrite($fp2,$img);
        fclose($fp2);
        unset($img,$url);
        return array('file_name'=>$filename,'save_path'=>$save_dir.$filename,'error'=>0);
    }

    /////////////////////////////////////////////////////生成压缩zip文件/////////////////////////////////////////////////////
    /**
     * 生成压缩zip文件
     * @param $file_name 最终生成的文件名,包含路径
     * @param $file_list,用来生成file_name的文件数组
     * makeZip('upload/product_qr_code/product_qr_code.zip',['upload/product_qr_code/cb01-000001-.jpg','upload/product_qr_code/cb01-000002-.jpg']);
     */ 
    public function makeZip($file_name, $file_list)
    {
        if (file_exists($file_name)) {
            unlink($file_name);
        }
        //重新生成文件
        $zip = new \ZipArchive();
        if ($zip->open($file_name, \ZIPARCHIVE::CREATE) !== TRUE) {
            exit('无法打开文件，或者文件创建失败');
        }
        foreach ($file_list as $val) {
            if (file_exists($val)) {
                $zip->addFile($val,basename($val));
            }
        }
        $zip->close();//关闭
        if (!file_exists($file_name)) {
            exit('生成数据包失败！请重新尝试！'); //即使创建，仍有可能失败
        }
    }

    //下载
    public function download($file){
        if ( file_exists ( $file )) {
            header ( 'Content-Description: File Transfer' );
            header ( 'Content-Type: application/octet-stream' );
            header ( 'Content-Disposition: attachment; filename=' . basename ( $file ));
            header ( 'Content-Transfer-Encoding: binary' );
            header ( 'Expires: 0' );
            header ( 'Cache-Control: must-revalidate' );
            header ( 'Pragma: public' );
            header ( 'Content-Length: ' . filesize ( $file ));
            ob_clean ();
            flush ();
            readfile ( $file );
            exit;
        }
    }
}