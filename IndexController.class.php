<?
public function ss(){
	echo 123;
}


public function member_list(){
	M('Member')->where(array('id'=>555))->select();
}