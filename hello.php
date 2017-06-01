<?php
/*
    public function groups_get_group_members2() {
        $this->init('groups');

        $oReturn = new stdClass();

        $mGroupExists = $this->get_group_from_params();
		
		if ($mGroupExists === false)
            return $this->error('base', 0);
        else if (is_int($mGroupExists) && $mGroupExists !== true)
            return $this->error('groups', $mGroupExists);
		
		$page = $_GET['page'];
		if(!$page){$page=1;}
		$per_page = $_GET['per_page'];
		if(!$per_page){$per_page=20;}
		$arg = array();
		$arg['group_id'] = $this->groupid;
		$arg['per_page'] = $per_page;
		$arg['page'] = $page;		
		$aMembers = groups_get_group_members($arg);
		$counter=0;
		$group_member_args = array(
					'group_id'	=>	$this->groupid,
					'page'		=>	$page,
					'per_page'	=>	$per_page,
				);
		global $members_template;
		if(bp_group_has_members($group_member_args)){
			while(bp_group_members()){
				bp_group_the_member();
				$aMember = $members_template->member;
				$oReturn->group_members[$counter]->id = $aMember->ID;
				$oReturn->group_members[$counter]->username = $aMember->user_login;
				$oReturn->group_members[$counter]->mail = $aMember->user_email;
				$oReturn->group_members[$counter]->display_name = bpaz_user_name_from_email($aMember->display_name);
				$oReturn->group_members[$counter]->nicename = $aMember->user_nicename;
				$oReturn->group_members[$counter]->registered = $aMember->user_registered;
				$oReturn->group_members[$counter]->last_activity = $aMember->last_activity;
				$oReturn->group_members[$counter]->friend_count = $aMember->total_friend_count;
				
				$user = new BP_Core_User($aMember->ID);
				if($user && $user->avatar){
					if($user->avatar_thumb){
						preg_match_all('/(src)=("[^"]*")/i',$user->avatar_thumb, $user_avatar_result);
						$avatar_thumb = str_replace('"','',$user_avatar_result[2][0]);
						if($avatar_thumb && !strstr($avatar_thumb,'http:')){ $avatar_thumb = 'http:'.$avatar_thumb;}
						$oReturn->group_members[$counter]->avatar = $avatar_thumb;
					}
				}				
				$profile_data = $user->profile_data;
				if($profile_data){
					foreach($profile_data as $sFieldName => $val){
						if(is_array($val)){
							$oReturn->group_members[$counter]->$sFieldName = $val['field_data'];
						}
					}
				}				
				if(function_exists('bp_follow_total_follow_counts')){
					$oReturn->group_members[$counter]->follow_counts  = bp_follow_total_follow_counts( array( 'user_id' => $aMember->ID ) );
				}
				$oReturn->group_members[$counter]->is_following = 0;
				if(function_exists('bp_follow_is_following') && $_GET['userid'] && bp_follow_is_following(array('leader_id'=>$aMember->ID,'follower_id'=>$_GET['userid']))){
					$oReturn->group_members[$counter]->is_following = 1;
				}
				$counter++;
			}
		}else{
            $oReturn->group_members = array();
            $oReturn->count = 0;
            return $oReturn;
        }
		$oReturn->count = $counter;
		//echo '<pre>';print_r($oReturn);
        return $oReturn;
    }
*/


class JSON_API_Hello_Controller {

/*
  public function hello_world() {
    return array(
      "message" => "Hello, world"
    );
  }
*/
  
    public function hello_world() {
  //       $this->init('groups');

  //       $oReturn = new stdClass();

  //       $mGroupExists = $this->get_group_from_params();
		
		// if ($mGroupExists === false)
  //           return $this->error('base', 0);
  //       else if (is_int($mGroupExists) && $mGroupExists !== true)
  //           return $this->error('groups', $mGroupExists);
		
		$page = $_GET['page'];
		if(!$page){$page=1;}
		$per_page = $_GET['per_page'];
		if(!$per_page){$per_page=20;}
		$arg = array();
		$arg['group_id'] = $this->groupid;
		$arg['per_page'] = $per_page;
		$arg['page'] = $page;		
		$aMembers = groups_get_group_members($arg);
		$counter=0;
		$group_member_args = array(
					'group_id'	=>	$this->groupid,
					'page'		=>	$page,
					'per_page'	=>	$per_page,
				);
		global $members_template;
		if(bp_group_has_members($group_member_args)){
			while(bp_group_members()){
				bp_group_the_member();
				$aMember = $members_template->member;
				$oReturn->group_members[$counter]->id = $aMember->ID;
				$oReturn->group_members[$counter]->username = $aMember->user_login;
				$oReturn->group_members[$counter]->mail = $aMember->user_email;
				$oReturn->group_members[$counter]->display_name = bpaz_user_name_from_email($aMember->display_name);
				$oReturn->group_members[$counter]->nicename = $aMember->user_nicename;
				$oReturn->group_members[$counter]->registered = $aMember->user_registered;
				$oReturn->group_members[$counter]->last_activity = $aMember->last_activity;
				$oReturn->group_members[$counter]->friend_count = $aMember->total_friend_count;
				
				$user = new BP_Core_User($aMember->ID);
				if($user && $user->avatar){
					if($user->avatar_thumb){
						preg_match_all('/(src)=("[^"]*")/i',$user->avatar_thumb, $user_avatar_result);
						$avatar_thumb = str_replace('"','',$user_avatar_result[2][0]);
						if($avatar_thumb && !strstr($avatar_thumb,'http:')){ $avatar_thumb = 'http:'.$avatar_thumb;}
						$oReturn->group_members[$counter]->avatar = $avatar_thumb;
					}
				}				
				$profile_data = $user->profile_data;
				if($profile_data){
					foreach($profile_data as $sFieldName => $val){
						if(is_array($val)){
							$oReturn->group_members[$counter]->$sFieldName = $val['field_data'];
						}
					}
				}				
				if(function_exists('bp_follow_total_follow_counts')){
					$oReturn->group_members[$counter]->follow_counts  = bp_follow_total_follow_counts( array( 'user_id' => $aMember->ID ) );
				}
				$oReturn->group_members[$counter]->is_following = 0;
				if(function_exists('bp_follow_is_following') && $_GET['userid'] && bp_follow_is_following(array('leader_id'=>$aMember->ID,'follower_id'=>$_GET['userid']))){
					$oReturn->group_members[$counter]->is_following = 1;
				}
				$counter++;
			}
		}else{
            $oReturn->group_members = array();
            $oReturn->count = 0;
            return $oReturn;
        }
		$oReturn->count = $counter;
		//echo '<pre>';print_r($oReturn);
        return $oReturn;
    }


}

?>
