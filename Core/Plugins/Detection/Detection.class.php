<?php
namespace Plugins\Detection;
use Think\Controller;

class Detection extends Controller {
	private $client;

	public function __construct() {
		parent::__construct();
		libxml_disable_entity_loader(false);
		$this->client = new \SoapClient(C('DETECTION_URL'));
	}

	/**
     * [createOrUpdateCustomer 修改或者增加一个客户]
     * @author StanleyYuen <[350204080@qq.com]>
     * $parameter['account']    [用户账号（必需项）] 
     * $parameter['password']    [用户密码（必需项）] 
     * $parameter['operation'] [操作字符串（新增则为”create”，修改则为”update”）] 
     * $parameter['id']      [客户唯一ID（由UUID生成，必需项）]
     * $parameter['name']    [客户名称（最大500字符长度，不可重复，必需项）]
     * $parameter['code']    [客户代码（最大50字符长度，不可重复，必需项）]
     * $parameter['address']    [客户地址（最大1000字符长度，非必需项）]
     * $parameter['telephone']    [客户电话（最大50字符长度，非必需项）]
     * $parameter['headName']    [负责人（最大50字符长度，非必需项）]
     * $parameter['headMobile']    [负责人电话（最大50字符长度，非必需项）]
     * $parameter['province']    [省份（省份对应字典的ID，非必需项）]
     * $parameter['city']    [城市（城市对应字典的ID，非必需项）]
     * $parameter['region']    [地区（地区对应字典的ID，非必需项）]
     */
	public function createOrUpdateCustomer($parameter) {
		$data = new \stdClass();
		$data->account = C('DETECTION_ACCOUNT');
		$data->password = C('DETECTION_PWD');
		$data->operation = $parameter['operation'];
		$data->id = $parameter['id'];
		$data->name = $parameter['name'];
		$data->code = $parameter['code'];
		$data->sex = $parameter['sex'] ? $parameter['sex'] : 1;
		$data->headimgurl = $parameter['headimgurl'] ? $parameter['headimgurl'] : '暂无';
		$data->telephone = $parameter['telephone'] ? $parameter['telephone'] : '13800138000';
		$data->regionId = $parameter['regionId'] ? $parameter['regionId'] : '111111';
		$data->givepiont = $parameter['givepiont'] ? $parameter['givepiont'] : 100;
		$data->totalSales = $parameter['totalSales'] ? $parameter['totalSales'] : 1;
		$data->manager = $parameter['manager'] ? $parameter['manager'] : '暂无';
		$data->status = $parameter['status'] ? $parameter['status'] : 1;
		$data->url = $parameter['url'] ? $parameter['url'] : '暂无';
		$data->address = $parameter['address'] ? $parameter['address'] : '暂无';
		$return = $this->client->createOrUpdateCustomer($data);
		$return = explode('|', $return->return);
		return $return;
	}

	/**
     * [deleteCustomer 删除一个客户信息]
     * @author StanleyYuen <[350204080@qq.com]>
     * $parameter['account']    [用户账号（必需项）] 
     * $parameter['password']    [用户密码（必需项）] 
     * $parameter['code']    [客户代码（必填）] 
     * $parameter['name']    [客户名称（最大500字符长度，不可重复，必需项）]
     */
	public function deleteCustomer($parameter) {
		$data = new \stdClass();
		$data->account = C('DETECTION_ACCOUNT');
		$data->password = C('DETECTION_PWD');
		$data->id = $parameter['id'];
		$data->name = $parameter['name'];
		$return = $this->client->deleteCustomer($data);
	}

	/**
     * [createOrUpdateAgency 修改或者增加一个代理商]
     * @author StanleyYuen <[350204080@qq.com]>
     * $parameter['account']    [用户账号（必需项）] 
     * $parameter['password']    [用户密码（必需项）] 
     * $parameter['operation'] [操作字符串（新增则为”create”，修改则为”update”）] 
     * $parameter['id']      [客户唯一ID（由UUID生成，必需项）]
     * $parameter['name']    [客户名称（最大500字符长度，不可重复，必需项）]
     * $parameter['code']    [客户代码（最大50字符长度，不可重复，必需项）]
     * $parameter['address']    [客户地址（最大1000字符长度，非必需项）]
     * $parameter['telephone']    [客户电话（最大50字符长度，非必需项）]
     * $parameter['headName']    [负责人（最大50字符长度，非必需项）]
     * $parameter['headMobile']    [负责人电话（最大50字符长度，非必需项）]
     * $parameter['province']    [省份（省份对应字典的ID，非必需项）]
     * $parameter['city']    [城市（城市对应字典的ID，非必需项）]
     * $parameter['region']    [地区（地区对应字典的ID，非必需项）]
     */
	public function createOrUpdateAgency($parameter) {
		$data = new \stdClass();
		$data->account = C('DETECTION_ACCOUNT');
		$data->password = C('DETECTION_PWD');
		$data->operation = $parameter['operation'];
		$data->id = $parameter['id'];
		$data->name = $parameter['name'];
		$data->code = $parameter['code'];
		$data->address = $parameter['address'] ? $parameter['address'] : '';
		$data->telephone = $parameter['telephone'] ? $parameter['telephone'] : '';
		$data->headName = $parameter['headName'] ? $parameter['headName'] : '';
		$data->headMobile = $parameter['headMobile'] ? $parameter['headMobile'] : '';
		$data->province = $parameter['province'] ? $parameter['province'] : '';
		$data->city = $parameter['city'] ? $parameter['city'] : '';
		$data->region = $parameter['region'] ? $parameter['region'] : '';
		$return = $this->client->createOrUpdateAgency($data);
		$return = explode('|', $return->return);
		return $return;
	}

	/**
     * [deleteAgency 删除一个代理商信息]
     * @author StanleyYuen <[350204080@qq.com]>
     * $parameter['account']    [用户账号（必需项）] 
     * $parameter['password']    [用户密码（必需项）] 
     * $parameter['code']    [客户代码（必填）] 
     * $parameter['name']    [客户名称（最大500字符长度，不可重复，必需项）]
     */
	public function deleteAgency($parameter) {
		$data = new \stdClass();
		$data->account = C('DETECTION_ACCOUNT');
		$data->password = C('DETECTION_PWD');
		$data->code = $parameter['code'];
		$data->name = $parameter['name'];
		$return = $this->client->deleteAgency($data);
		return $return;
	}

	/**
     * [findAllPlans 查询系统中所有套餐]
     * @author StanleyYuen <[350204080@qq.com]>
     * $parameter['account']    [用户账号（必需项）] 
     * $parameter['password']    [用户密码（必需项）]
     */
	public function findAllPlans($parameter) {
		$data = new \stdClass();
		$data->account = C('DETECTION_ACCOUNT');
		$data->password = C('DETECTION_PWD');
		$return = $this->client->findAllPlans($data);
		$return = explode('|', $return->return);
		return json_decode($return[1], true);
	}

	/**
     * [specimenInput 样本录入功能]
     * @author StanleyYuen <[350204080@qq.com]>
     * $parameter['account']    [用户账号（必需项）] 
     * $parameter['password']    [用户密码（必需项）] 
     * $parameter['barcode'] [条码号（12位数字，并以00结尾，必需项）] 
     * $parameter['patientName']      [病人姓名（必需项）]
     * $parameter['sex']    [性别（1位数字，0为未知，1为男，2为女，必需项）]
     * $parameter['birthday']    [出生日期（格式形如2016-07-06，必需项）]
     * $parameter['sampleDate']    [采样日期（格式形如2016-07-06，必需项）]
     * $parameter['province']    [省份（32位字符串，必需项）]
     * $parameter['city']    [城市（32位字符串，必需项）]
     * $parameter['expressNumber']    [快递单号（必需项）]
     * $parameter['expressCompany']    [快递公司名称（必需项）]
     * $parameter['mobileNumber']    [联系电话（必需项）]
     * $parameter['customerId']    [下单客户ID（32位字符串，必需项）]
     * $parameter['plansId']    [套餐ID（32位字符串，必需项）]
     * $parameter['age']    [年龄（非必需项）]
     * $parameter['idCardNum']    [身份证号（非必需项）]
     * $parameter['region']    [地区（32位字符串，非必需项）]
     * $parameter['familyHistory']    [家族史（最大500字符长度，格式形如："母亲患有房颤;奶奶患有中风;"，非必需项）]
     * $parameter['customerAddress']    [客户地址（最大1000字符长度，非必需项）]
     * $parameter['remark']    [备注（非必须项）]
     */
	public function specimenInput($parameter) {
		$data = new \stdClass();
		$data->account = C('DETECTION_ACCOUNT');
		$data->password = C('DETECTION_PWD');
		$data->barcode = $parameter['barcode'];
		$data->patientName = $parameter['patientName'];
		$data->sex = $parameter['sex'];
		$data->birthday = $parameter['birthday'];
		$data->sampleDate = $parameter['sampleDate'];
		// $data->province = $parameter['province'];
		// $data->city = $parameter['city'];
		// $data->expressNumber = $parameter['expressNumber'];
		// $data->expressCompany = $parameter['expressCompany'];
		$data->mobileNumber = $parameter['mobileNumber'];
		$data->customerCode = $parameter['customerCode'];
		$data->plansId = $parameter['plansId'];
		$data->age = $parameter['age'] ? $parameter['age'] : '';
		$data->idCardNum = $parameter['idCardNum'] ? $parameter['idCardNum'] : '';
		$data->region = $parameter['region'] ? $parameter['idCardNum'] : '';
		$data->familyHistory = $parameter['familyHistory'] ? $parameter['familyHistory'] : '';
		$data->customerAddress = $parameter['customerAddress'] ? $parameter['customerAddress'] : '';
		$data->remark = $parameter['remark'] ? $parameter['remark'] : '';
		$data->agencyCode = $parameter['agencyCode'];

		$return = $this->client->specimenInput($data);
		$return = explode('|', $return->return);
		return $return;
	}

	/**
     * [loadReport 报告加载功能]
     * @author StanleyYuen <[350204080@qq.com]>
     * $parameter['account']    [用户账号（必需项）] 
     * $parameter['password']    [用户密码（必需项）] 
     * $parameter['barcode'] [条码号（必填项）] 
     */
	public function loadReport($parameter) {
		$data = new \stdClass();
		$data->account = C('DETECTION_ACCOUNT');
		$data->password = C('DETECTION_PWD');
		$data->barcode = $parameter['barcode'];
		$return = $this->client->loadReport($data);
		$return = explode('|', $return->return);
		return $return;
	}
}