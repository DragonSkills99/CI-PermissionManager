<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permissions {
    
    private $db;
    private $ci;
    private $haspermissions;
    private $id;
    private $users;
    
    public function __construct($uid = null){
        $this->ci = get_instance();
        $this->ci->load->database();
        $this->ci->load->library('lUsers');
        $this->db = $this->ci->db;
        $this->users = $this->ci->lusers;
        if(!is_numeric($uid)){
            if($this->users->loggedIn()){
                $this->haspermissions = true;
                $this->id = $this->users->getCurrentUserID();
            }
            else $this->haspermissions = false;
        }
        else{
            $q = $this->db->get_where('users', array('id' => $uid));
            $row = $q->result();
            $this->haspermissions = $q->num_rows() > 0;
            $this->id = $uid;
        }
        $this->reloadPermissionList();
        if(!$this->existsPermission("superuser")) $this->addPermission("superuser", "superuser");
        if(!$this->existsPermission("add permission")) $this->addPermission("add permission", "superuser");
        if(!$this->existsPermission("delete permissions")) $this->addPermission("delete permissions", "superuser");
        if(!$this->existsPermission("list users")) $this->addPermission("list users", "superuser");
        if(!$this->existsPermission("edit permission")) $this->addPermission("edit permission", "superuser");
    }
    
    public function setPermission($permission, $userid, $state){
        $id = $this->getPermissionID($permission);
        if($id == -1) return false;
        if($state){
            $this->db->insert('haspermission', array('id_users' => $userid, 'id_permission' => $id));
            return $this->db->affected_rows() > 0;
        }
        else{
            $this->db->delete('haspermission', array('id_users' => $userid, 'id_permission' => $id));
            return $this->db->affected_rows() > 0;
        }
    }
    
    private $permissions;
    
    private function reloadPermissionList(){
        $this->permissions = $this->db->get('permission')->result();
    }
    
    public function getUsersWith($permission){
        $users = array();
        $perms = $this->db->get_where('haspermission', array('id_permission' => $this->getPermissionID($permission)))->result();
        foreach($perms as $perm){
            array_push($users, $this->users->getUserByID($perm->id_users));
        }
        return $users;
    }
    
    public function getPermissions(){
        return $this->permissions;
    }
    
    public function addPermission($name, $needed){
        if(!$this->existsPermission($needed) && $needed != $this->slugify($name)) show_error("Your needed Permission doesn't exist.", 400, "Insert Error");
        if($this->existsPermission($name) || $this->existsPermission($this->slugify($name))) show_error("This permission already exists.", 400, 'Insert Error');
        if($needed != $this->slugify($name)) $needed = $this->getPermission($needed)->slug;
        $this->db->insert('permission', array('name' => $name, 'slug' => $this->slugify($name), 'need_for_access' => $needed));
        $this->reloadPermissionList();
        return $this->db->affected_rows() > 0;
    }
    
    public function slugify($text){
            // replace non letter or digits by -
      $text = preg_replace('~[^\pL\d]+~u', '_', $text);
    
      // transliterate
      $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    
      // remove unwanted characters
      $text = preg_replace('~[^-\w]+~', '', $text);
    
      // trim
      $text = trim($text, '_');
    
      // remove duplicate -
      $text = preg_replace('~-+~', '_', $text);
    
      // lowercase
      $text = strtolower($text);
    
      if (empty($text)) {
        return 'n_a';
      }
    
      return $text;
    }
    
    public function getUserPermissions($uid = null){
        if($uid === null || !is_numeric($uid)){
            if(!$this->haspermissions) return array();
            return $this->db->get_where('haspermission', array('id_users' => $this->id))->result();
        }
        else{
            return $this->db->get_where('haspermission', array('id_users' => $uid))->result();
        }
    }
    
    public function hasPermission($id, $userpermissions = null, $checked = array(), $inherit = true){
        if($checked === null) $checked = array();
        if(!$this->haspermissions) return false;
        if(is_array($id)){
            $retu = true;
            foreach($id as $ID){
                $retu &= $this->hasPermission($ID);
            }
        }
        else if(is_numeric($id)){
            $pid = $id;
        }
        else if(is_string($id)){
            $pid = $this->getPermissionID($id);
            if($pid == -1) return false;
        }
        else return false;
        if($userpermissions === null) $userpermissions = $this->getUserPermissions();
        
        $has = false;
        foreach($userpermissions as $perm){
            if($perm->id_permission == $pid) $has = true;
        }
        if(!$inherit) return $has;
        $permission = $this->getPermission($pid);
        array_push($checked, $permission->slug);
        if(!$has && $permission->need_for_access != $permission->slug && !in_array($permission->need_for_access, $checked)) return $this->hasPermission($permission->need_for_access, $userpermissions, $checked);
        return $has;
    }
    
    public function needPermission($id){
        $perm = $this->getPermission($id); 
        if($perm === null) $slug = $id; else $slug = $perm->slug;
        if(!$this->hasPermission($id)) show_error("You're not permitted, visiting this page<br>(missing '$slug')", 400, "Permission Error");
    }
    
    public function getPermission($permission){
        $id = $this->getPermissionID($permission);
        foreach($this->permissions as $perm) if($perm->id == $id) return $perm;
        return null;
    }
    
    public function getPermissionsByLevel($level){
        $l = $this->getPermission($level);
        $res = array();
        foreach($this->permissions as $perm) if($perm->need_for_access == $l->slug) array_push($res, $perm->id);
        return $res;
    }
    
    public function updatePermissionLevel($permission, $level){
        $p = $this->getPermission($permission);
        $l = $this->getPermission($level);
        $inheriting = $this->getInheriting($p->id);
        if(in_array($l->id, $inheriting)) return false;
        if($p === null || $l === null) return false;
        $this->db->update("permission", array('need_for_access' => $l->slug), array('id' => $p->id));
        return $this->db->affected_rows() > 0;
    }
    
    public function getInheriting($permission, $userid = null, $res = null, $permissions = null){
        $p = $this->getPermission($permission);
        if($permissions === null) $permissions = new Permissions($userid);
        $userpermissions = $permissions->getUserPermissions();
        if(!is_array($res)) $res = array();
        $direct = $this->getPermissionsByLevel($p->id);
        foreach($direct as $permid){
            if($permid == $p->id) continue;
            if($userid === null){
                if(!in_array($permid, $res)){
                    array_push($res, $permid);
                    $res = $this->getInheriting($permid, null, $res);
                }
            }
            else{
                if(!in_array($permid, $res) && !$permissions->hasPermission($permid, null, null, false)){
                    array_push($res, $permid);
                    $res = $this->getInheriting($permid, $userid, $res, $permissions);
                }
            }
        }
        return $res;
    }
    
    public function getPermissionID($slug){
        $slug = $this->slugify($slug);
        if(is_numeric($slug)){
            foreach ($this->permissions as $perm) if($perm->id == $slug) return $slug;
            return -1;
        }
        else if(is_string($slug)){
            foreach ($this->permissions as $perm) if($perm->slug == $slug) return $perm->id;
            return -1;
        }
        else return -1;
    }
    
    public function existsPermission($id){
        if(is_array($id)){
            $retu = true;
            foreach($id as $ID){
                $retu &= $this->existsPermission($ID);
            }
        }
        else if(is_numeric($id) || is_string($id)){
            return $this->getPermissionID($id) != -1;
        }
        else return false;
    }
    
    public function deletePermission($permission){
        $p = $this->getPermission($permission);
        if($p === null) return false;
        $this->db->delete('permission', array('id' => $p->id));
        return $this->db->affected_rows() > 0;
    }
}

?>