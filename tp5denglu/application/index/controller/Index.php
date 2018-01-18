<?php
namespace app\index\controller;

use think\Controller;
use app\index\controller\Common;

class Index extends Controller{
    public function index()//登录页
    {
		session('username',null);//删除（当前作用域）
		if(cookie('username')!='')//记住用户的登录信息保留多长时间
		{
			session('username',cookie('username'));
		}
		if(session('username')!='')//记住用户的id  登录信息
		{
			$this->redirect('index/welcome');
		}
		return $this->fetch();
    }
	public function do_login()
	{
		$id=input('post.Id');
		$username=input('post.username');
		$password=input('post.password');
		$info=db('user')->where('username="'.$username.'"')->find();
		if($info){
			if($info['password']==md5($password)){
				session('username',$username);
				session('userid',$info['Id']);
				if(input('post.ischecks')==1){
					cookie('username',$username,3600);//记住登录保留的时间
				}
				$datas=[
					'msg'=>'登录成功',
					'status'=>1
					];
			}else{
				$datas=[
					'msg'=>'登录失败，密码错误',
					'status'=>2
					];
			}
		}else{
			$datas=[
				'msg'=>'登录失败，用户名不存在',
				'status'=>3
				];
		}
		return json($datas);
	}
	public function welcome()//欢迎页
    {
		return $this->fetch();
    }
	public function zhuce()//注册 验证码 图片上传
	{
		return $this->fetch();
	}
	public function do_zhuce()
	{
		
		$data=input('post.');
		$data['password']=md5($data['password']);
		$file=request()->file('photo');
		if($file){
			$fileinfo=$file->move(config('upload_path'));
			$data['photo']=$fileinfo->getSavename();
		}
		
		// $extarr=explode('.',$_FILES['photo']['name']);
      	// $ext = $extarr[count($extarr)-1];
      	// $photo_path= ROOT_PATH."public/Uploads";        //  ThinkPHP5 的ROOT_PATH
      	// is_dir($photo_path)?'':mkdir($photo_path,0777);
      	// 数据库图片名称  地址+名称
      	// $photo="http://www.XX.cn/photopath/".time().'.'.$ext;
      	// $fileInfo=move_uploaded_file($_FILES['photo']['tmp_name'],$photo_path.time().'.'.$ext);
		// $data['photo']=$photo;
		
		$data['inputtime']=date('Y-m-d',time());
		if(captcha_check($data['captcha'])){
			unset($data['captcha']);
			$info=db('user')->insert($data);
			if($info){
				$this->success('注册成功','index/index');
			}else{
				$this->error('注册失败');
			}
		}else{
			$this->error('验证码错误');
		}
	}
	public function userlist()//列表页
	{
		$db=db('user');
        $list=$db->paginate(4);
        $this->assign('list',$list);
        $this->assign('page',$list->render());
		return $this->fetch();
	}
}
