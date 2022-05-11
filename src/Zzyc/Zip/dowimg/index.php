<?php
    require_once("zipTool.php");

    /**
     * 为图像添加图片水印
     *
     * @param 参数1 原图
     * @param 参数2 需要添加的水印图片
     * @param 参数3 要添加的位置，默认是0，有10个位置可选（0，1，2，3，4，5，6，7，8，9）
     * @param 参数4 透明度 默认100
     * @param 参数5 增加完水印的图片的前缀
     * @param 参数6 是否保存图片（0-》保存，1-》浏览器输出）
     */
    $imgTool1 = new zipTool('water/');
    $image1 = './imgs/123456.jpg';
    $waterImg = './imgs/water.png';
	//echo $imgTool1->addWater($image1,$waterImg,0,100,'water_',0);

	/**
     * 为图像添加文字标记
     *
     * @param 参数1 原图片
     * @param 参数2 文本内容
     * @param 参数3 字体大小
     * @param 参数4 字体样式 ttf文件
     * @param 参数5 x坐标位置
     * @param 参数6 y坐标位置
     * @param 参数7 是否保存图片（0-》保存，1-》浏览器输出）
     */
	$imgTool2 = new zipTool('textmark/');
	$image2 = './imgs/123456.jpg';
	//echo $imgTool2->addTextmark($image2,"新泽网络",30, dirname(__FILE__).".\imgs\msyh.ttf",100,110,0);

	/**
	 *  图片压缩
	 *
     * @param 参数1 原图片
     * @param 参数2 范围从 0（最差质量，文件最小）到 100（最佳质量，文件最大）
     * @param 参数3 是否保存到指定路径
     */
	$imgTool3 = new zipTool('compressimg/');
	$image3 = './imgs/123456.jpg';
	//echo $imgTool3->compressImg($image3,20,0);

	/**
     * 远程图片下载工具
     *
     * @param 参数1 远程文件url
     * @param 参数2 要保存到本地的目录
     * @param 参数3 文件名称
     * @param 参数4 获取远程文件所采用的方法 0为curl
     */
	$imgTool4 = new zipTool('商品数据包/');

	//下载封面图
	$cover = 'https://cbu01.alicdn.com/img/ibank/2020/957/967/13666769759_1525193136.jpg';
	$imgTool4->downLoadImg($cover,'./商品数据包','封面图.jpg',0);
	$file_list[0] = './商品数据包/封面图.jpg';
    //$imgTool4->compressImg1($file_list[0],90);
	$file_list[1] = './商品数据包/product.csv';

	//下载轮播图
	$shuffling = 'https://cbu01.alicdn.com/img/ibank/2020/957/967/13666769759_1525193136.jpg,https://cbu01.alicdn.com/img/ibank/2020/366/277/13666772663_1525193136.jpg,https://cbu01.alicdn.com/img/ibank/2020/016/097/13666790610_1525193136.jpg';
	$shufflings = explode(',', $shuffling);
	$i =1;
    foreach ($shufflings as $shuffling_img) {
        $imgTool4->downLoadImg($shuffling_img,'./商品数据包','轮播图_'.$i.'.jpg',0);
        $file_list[$i+1] = './商品数据包/轮播图_'.$i.'.jpg';
        //$imgTool4->compressImg1($file_list[$i+1],90);
        $i ++;
    }

    //下载详情图
    $details = '<p><img src="http://cbu01.alicdn.com/img/ibank/O1CN01Ubgfwh1xo0kyXBdaP_!!2239856489-0-cib.jpg"/><img src="//cbu01.alicdn.com/img/ibank/2018/439/031/9143130934_1306809027.jpg"/><img src="//cbu01.alicdn.com/img/ibank/2018/576/631/9143136675_1306809027.jpg"/><img src="https://cbu01.alicdn.com/img/ibank/2018/151/361/9143163151_1306809027.jpg"/><img src="//cbu01.alicdn.com/img/ibank/2018/384/508/9184805483_1306809027.jpg"/></p>';

	if (preg_match('/(http:)|(https:)/i', $details)) {
	    $details = preg_replace('/(http:)|(https:)/i', '', $details);
	}
    preg_match_all('/<img[^>]*?src="([^"]*?)"[^>]*?>/i',$details,$match);
    $detailss = $match[1];
	//var_dump($match[1]);
    
	//$details = 'https://cbu01.alicdn.com/img/ibank/2020/957/967/13666769759_1525193136.jpg,https://cbu01.alicdn.com/img/ibank/2020/366/277/13666772663_1525193136.jpg,https://cbu01.alicdn.com/img/ibank/2020/016/097/13666790610_1525193136.jpg,https://cbu01.alicdn.com/img/ibank/2020/904/319/13620913409_1525193136.jpg,https://cbu01.alicdn.com/img/ibank/O1CN01hsSqKu1Bs2k5wRy6y_!!0-0-cib.jpg';
	//$detailss = explode(',', $details);

	$j =1;
    foreach ($detailss as $details_img) {
        $imgTool4->downLoadImg('http:'.$details_img,'./商品数据包','详情图_'.$j.'.jpg',0);
        $file_list[$j+$i] = './商品数据包/详情图_'.$j.'.jpg';
        //$imgTool4->compressImg1($file_list[$j+$i],90);
        $j ++;
    }

    //下载视频
	$video_link = 'http://www.conbagroup.com/mtsc/uploads/Menu/Extend/202011300830477534804.mp4';
	if($video_link != ''){
		$imgTool4->downLoadImg($video_link,'./商品数据包','视频.mp4',0);
		$file_list[$j+$i] = './商品数据包/视频.mp4';
	}
	
    //打包下载文件
    $file_name = './商品数据包/卫食园汤汁腊鸭650g彩袋包装风干板鸭安徽特产农家自制咸鸭子整只.zip';
    sleep(1);
    $imgTool4->makeZip($file_name,$file_list);
    sleep(1);
    $imgTool4->download($file_name);