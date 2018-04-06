<?php
namespace Plugins\Controller;
use Think\Controller;
class MapController extends Controller 
{
	private $webSite = 'http://www.baiyangwang.com';
	/**
	 * 生成百度sitemap xml文件
	 *
	 */
	public function sitemap()
	{
        $sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\r\n";
        //首页
        $sitemap .= "\t<url>\r\n\t\t";
        $sitemap .= "<loc>".$this->webSite."</loc>\r\n\t\t";
        $sitemap .= "<changefreq>always</changefreq>\r\n\t\t";
        $sitemap .= "<priority>1.0</priority>\r\n\t";
        $sitemap .= "</url>\r\n";
        
        //分类列表
        $categoryList = M('goodsCategory')->field('id')->select();
        $sitemap .= $this->forSite($categoryList, 'daily', '0.8', '/list-', 'id');
        
        //品牌
        $brandList = M('Brand')->field('id')->select();
        $sitemap .= $this->forSite($brandList, 'weekly', '0.8', '/brand/', 'id');
        
        //产品
        $goodsList = M('Goods')->field('id')->where(array('is_on_sale'=>array('eq', 1)))->select();
        $sitemap .= $this->forSite($goodsList, 'weekly', '0.9', '/product/', 'id');
        
        $sitemap .= '</urlset>';
        
        $path = './Static/Sitemap';
        if (!file_exists($path)) 
		{ 
			mkdir($path, 0777);
		}
        $file = fopen($path."/sitemap-mall.xml", "w");
        fwrite($file, $sitemap);
        fclose($file);
        $this->success('生成成功');
	}
	
	/**
	 * 遍历数组生成xml
	 *
	 * @param unknown_type $list 数据
	 * @param unknown_type $changefreq
	 * @param unknown_type $priority
	 * @param unknown_type $url 生成网站路径
	 * @param unknown_type $string 数据字段
	 * @return unknown xml字符串
	 */
	private function forSite($list, $changefreq, $priority, $url, $string)
	{
		$data = '';
		foreach($list as $k => $v){
            $data .= "\t<url>\r\n\t\t";
            $data .= "<loc>".$this->webSite.$url.$v[$string].'.html'."</loc>\r\n\t\t";
            $data .= "<changefreq>$changefreq</changefreq>\r\n\t\t";
            $data .= "<priority>$priority</priority>\r\n\t";
            $data .= "</url>\r\n";
        }
        return $data;
	}
}
?>