
## zzyc-supply-download-zip

zzyc-supply-download-zip是中泽云仓官方SDK的Composer封装，支持php项目的中台商品打包下载。
## 安装

* 通过composer，这是推荐的方式，可以使用composer.json 声明依赖，或者运行下面的命令。
```bash
$ composer require zzyc-supply/download-zip
```
* 直接下载安装，SDK 没有依赖其他第三方库，但需要参照 composer的autoloader，增加一个自己的autoloader程序。

## 运行环境

    php: >=7.0

## 使用方法

```php    

	/**
     * Created by PhpStorm.
     * User: Zzyc
     * Date: 2022/4/17
     * Time: 2:04 PM
     */
    
    use Zzyc\Zip\zipTool;

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
	echo $imgTool1->addWater($image1,$waterImg,0,100,'water_',0);

```    

## 供应链平台

官网网址 https://www.xzwl1688.com/  

浙江中台H5网址 https://zj.center.xzwl1688.com/  

安徽中台H5网址 https://ah.center.xzwl1688.com/  

江西中台H5网址 https://jx.center.xzwl1688.com/  