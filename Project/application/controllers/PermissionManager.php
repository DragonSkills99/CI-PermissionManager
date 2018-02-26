<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PermissionManager extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('image');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('permissions');
        $this->load->library('lUsers');
        $this->load->library("FormBuilder");
        $this->users = $this->lusers;
    }
    
    public function keys($type = "all"){
        header("Content-Type: image/png");
        switch ($type) {
            default:
            case 'all':
                echo base64_decode("iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAACXBIWXMAAA7EAAAOxAGVKw4bAAACu0lEQVRYhe2Xz0sUYRjHPzNNtiwjTCJKIOwigeChSDxtIFFRwqJhROzJOnRQ2A6CQn9AnsOj4MXTnkQvVmRLFiVsICEaXgSJDl0Uysr9MTNOl93Z+bk6uzvSwS88zK9n5v28333f93kXzuStc4HfiKFwEZk8BXSMEJhqqBOFKdKskaXALzR0dAxUimyxzgum6aU7XAgJkUnGOGAfHQOtHLrHsUSROWZoJdp8EJkWlsiglxs7aWyyQQ9dzQOREFkiYzphdcHLGef9HbbpoK05MJOM2Xpbosg8szzmAU9IsUgGvTxu/GKRTOMgnSgcsG/2tkSRJDddeeOMoqP7jiENgyQDjcFMkbb1cJ5Z39xVXtV0Z5mFoM2LtqsRRsyVwwDesVIDJmvLdR5vcQeZSP0wffTbPioh+b7pfOYEkpC5Qm99MDEUJGQEMCPJPZ+3RAYZst0TPOJysGlehREQERAxwIxhHjLOqAvkOc+4RgKwO+IMoYazHhLMswuI/CGPSIstw+CIj7xhlSwSEoMMmSDVHOuXqrrLdd6yFgSoqi3WfRc7Zxy3+JVQuYQSpHn7AF7hte03B++xYH3mzKlcf+UzP/gZ0A6LeummRPHY3p/EGQ2VCVL1wwDMMRO4QPpFw0CtRNlkI7AzfseGgXroYoft/8ehDtrKFdq9hXA35r/V0JoFBJBkgGUWKPDbBVFC5Quf0FBPx6GKZCIk6GOUYR5xn9skzHVkghQaqmvMeI+jJgHV0lNS7JJjlxzfyxPAP04BqKIYcZdDbqdU0qcBFCNe05kKVIE87dV/FIGq6olV4JAPvARAQeGqpbBWqrwAnCeCjMweh6FwuHSDft/Cq2MQp6OSGo4zTjm3F17bDZxVOwz95aDsg13V66PQGWxKk6JA3jWzcry3uuRjWAhqJ4qMbLlzxDf2XI6dyUP/AMWvdZ5VDYrBAAAAAElFTkSuQmCC");
                break;
            case 'none':
                echo base64_decode("iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAACXBIWXMAAA7EAAAOxAGVKw4bAAACy0lEQVRYhe3Yz2scZRzH8ddu1xrCFNZSUgqFhCIUclAsPUUoomILoZWKyJ6iBw8txEOhBf8Ae5YeC730lFNJL1WxDVbRQoQipZVeCkU8eGlBozb7YybjoZn4ZJzZZpLdoOAHvsw8z3xnnvfz2Wee55nlfxVrR9Ubxmm+QLRMOyEdBlSp9tI8x+wtFtr8FpMkpD0697j9KecnOTBUiAb1s5xa4nFCGq9GUnDs0rnEhV2MDhwkYudV5pLVxjYad7lzkP0DA2lQv8pcXOBGkTP5+gfcH2P3QGDOcirsbZfOZS5+wLsf0pp/6ljSz6F55rYMspfmEo+z3nbpTPN6Pu80MwlJ2RiKSac5siWYc8yGPbzMxbLcm3zez51rXKnafj0snORkNnGk+IrrfWAWwtz88Q3eihjZNMwhDocPbdAouzF/LQ/UIHqJyU3BjNNsENWQxTRvl9xUP8bxsK5WEC9WfM3XYGrUa9RTT3uX4gTvnWYmD/IJH7/CFOsdyUetj7NFqmUnz1P/g+U6O8OElJVv+fImCw0axziegQQ5fz8o0FFevcGtKkBrusftsskuH8+a/Lr09tGs0v66AXydL8LfnOKxEF7L52TlH/n+F36tasiaJjnQpfOs3m/EmZjeGVqbhoFLXKi6QJbFloF2MXqXO1WdKTtuGegg+x9w/1/j0Bi754M9TehIQWOlW414UEAwzZFrXGnzex6iS+8HvovpbYtDmSJGpjg0w4n3eedNprJ55AytmF5+zJSMo8EA9dNHtB6y+JDFn1dfgD4xfKBM40zkHSpwqje7HUDjTPRzJoNqs7wn+KKotKpuVG2efMNn0KT5crCwZqt8Dc8xEhE94skwOP6h1zhctvAmpBOMZblDcSav/PaiaLtBbtUehv5kKSXOf5QH5ZVhM6zTLK02y/k3a5GvQ5fKHBu49jAaEQVVKz/xaHv/xviv6i8LpXWevGiHRAAAAABJRU5ErkJggg==");
                break;
            case 'inherit':
                echo base64_decode("iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAACXBIWXMAAA7EAAAOxAGVKw4bAAADPElEQVRYhe1XS0sbURT+Zjo+kAlESRMEYUSEQBZKRCikIFK1CkFbSylZRRcuDMSFYKA/wK6lS8FNVq5EN2lLVbRixRRExIiLBoK4cKHSNj4ymcx1utBMMq/o5OHKDw73MefmfvebM/ecAE/QxzPTKzjOivp6FqkUD0KkCnAqAIfDilAoiK2tVfD8P4giASESMpk0YrEdzMx8gsvVUlkSDENjamocyeQ5CJEgireW7ee3gpDG3NxnWCx15SfCstVYWpoHIZIp29/fg9PZVD4iDENjaWleViJfBT1l1PPx+CHs9obykJmaGlecVhDSCIdnMTr6HmNjPiwuzoMQUlChxcX50ok4HFYkk+fyaQUhDa/3lcYvEPCDEGIYQ6IowevtKo1MKBRUnDAcnjX0XV//WlCdSGTB7Pa0YjQ8PAzp7uqQJGBtbbkAmVWFr7rt6XkNlq0tnkxHR6fiRxmGMVypfqYmxDAs2tpcxZHhOCsYhgVFQTav943+KprGwMCgYi5/XdZaW0195jkyFEWDomhIEmQbGvqAQMCvITI9/RFutweAUhG1UZSxsjqg5F5NDY3LyxRoulrhIUk32Nz8jvX1VTAMg4GBQZlIzudWCTX6+19iZWXLDKEcYrEdw8tObfddfoKQQWOj1cz2ygBeXv6meOeAfizkP1P7ZMcHB79wcvK3OFUAwOVqgSCk7z39Q5QRxQwmJ31mtlfWM6enf8Bxz+F2vyh4+ocoQ1E0+vre4uLiN7a3Y8WpY7HUYX9/z7QyRm0RCinhdDYhHj80XUIYWcmE7PaGuwytLSG0mxmXGrl+iYQAwOvtQiSyAJ6/0JAQhAx2d39CFDOPo1AWLFsLj6cDfv8QRkbeobfXI98jk5M+iGJGEzP6cVQmQoUwMeFDIhFFIhHF8fGe4WVZlqA2A45r1iikVSqDYPARCHFcc0FlsqR4PgWbTf5HYSqrPhg8f42NjS8AAKvVivb2XGLNZnmKAqqqasGyLM7OrivCQ4Pu7k7DxEuIhOZme9a1MsqooS4v9MoNqLN2JXB1lYQkifLrySI3vqk4BwWCQR94PqX5sqLRH/kq6etVCdhsdWBZNm/mBkdHZxrFnqCD/81Iidrq883pAAAAAElFTkSuQmCC");
                break;
        }
    }
    
    public function installation(){
        if(count($this->permissions->getUsersWith("superuser")) <= 0){
            $this->formbuilder->setMethod("POST");
            $sel = $this->formbuilder->addField()->setTag("select")->setFillBothRows(true)->setName("name")->setRule("required")->setDescription("Number");
            foreach($this->users->getUsers() as $user){
                $sel->addChild()->setTag('option')->setValue($user->id)->setInnerValue($user->name);
            }
            $this->formbuilder->addField((new Field())->setType("submit")->setName("submit")->setValue("Complete installation")->setFillBothRows(true));
            $data = array('title' => 'Permission Setup');
            $this->formbuilder->setup();
            if ($this->formbuilder->validate())
            {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/echo', array('echo' => $this->formbuilder->getForm()));
                $this->load->view('templates/footer');
            }
            else
            {
                $name = $this->input->post('name');
                $this->load->view('templates/header', $data);
                if($this->permissions->setPermission('superuser', $name)){
                    $this->load->view('templates/echo', array('echo' => '<h1>Setup completed successfully.</h1>'));
                }
                else{
                    $this->load->view('templates/echo', array('echo' => '<h1>Setup failed.</h1>'));
                }
                $this->load->view('templates/footer', array());
            }
        }
        else{
            show_error('Already installed.', 405, 'Installation error');
        }
    }
    
    public function install(){
        if(!$this->db->table_exists("permission")){
            $this->db->query("
CREATE TABLE `permission` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `slug` text NOT NULL,
 `name` text NOT NULL,
 `need_for_access` text NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8
            ");
        }
        if(!$this->db->table_exists("haspermission")){
            $this->db->query("
CREATE TABLE `haspermission` (
 `id_users` int(11) NOT NULL,
 `id_permission` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8
            ");
        }
        echo "<h1>Installation Completed</h1>";
    }
    
    public function users(){
        if(!$this->permissions->hasPermission("list users")) show_error("Not permitted", 400, "Authentification error");
                $echo = "";
        $echo .= "<style>";
        $echo .=    "
                    .permtable {
                        width: 100%;
                        border-collapse:collapse;
                        border-spacing:0;
                        border-color:#fff;
                    }
                     .permtable td{
                        font-family:Arial, sans-serif;
                        font-size:14px;
                        padding:10px 5px;
                        border-style:solid;
                        border-width:1px;
                        overflow:hidden;
                        word-break:normal;
                        border-color:#fff;
                        color:#333;
                        background-color:#fff;
                    }
                    .permtable tr{
                          -webkit-touch-callout: none; /* iOS Safari */
                            -webkit-user-select: none; /* Safari */
                             -khtml-user-select: none; /* Konqueror HTML */
                               -moz-user-select: none; /* Firefox */
                                -ms-user-select: none; /* Internet Explorer/Edge */
                                    user-select: none; /* Non-prefixed version, currently
                                                          supported by Chrome and Opera */
                    }
                    .permtable tr:hover:not(.nu){
                        text-decoration: underline;
                    }
                    .permtable tr:hover td{
                        background-color: #252525;
                    }
                     .permtable th{
                        font-family:Arial, sans-serif;
                        font-size:14px;
                        font-weight: bold;
                        padding:10px 5px;
                        border-style:solid;
                        border-width:1px;
                        overflow:hidden;
                        word-break:normal;
                        border-color:#fff;
                        color:#fff;
                        background-color:#006;
                    }
                     .permtable td{
                        background-color:#000;
                        color: #fff;
                    }
                    .btn {
                        color: white;
                        background-color: blue;
                        padding: 5px;
                        border-color: #fff;
                        border-style: solid;
                        border-width: 1px;
                    }
                    .fright {
                        float: right;
                    }
                    .fclear {
                        clear: both;
                    }
                    ";
        $echo .= "</style>";
        $echo .= "<table class=\"permtable\">";
        $echo .= "    <tr class=\"nu\">";
        $echo .= "        <th>ID</th>";
        $echo .= "        <th>Name</th>";
        $echo .= "    </tr>";
        $echo .= "<tnoscript><h1>To use this page you need to activate JavaScript.</h1></tnoscript>";
        foreach($this->users->getUsers() as $user){
            $echo .= "<tr class=\"href\" data-href=\"".base_url("PermissionManager/user/".$this->users->getID($user))."\">";
            $echo .= "<td>".$this->users->getID($user)."</td>";
            $echo .= "<td>".$this->users->getUsername($user)."</td>";
            $echo .= "</tr>";
        }
        $echo .= "</table>";
        $echo .= "<script>
                    $(\"tnoscript\").remove();
                    $(\".href\").click(function() {
                        window.location = $(this).data(\"href\");
                    });
                  </script>";
        $echo .= "<br><a class=\"btn fright\" href=\"".base_url("permissionManager/add")."\">Add Permission-Entry</a><div class=\"fclear\"></div>";
        $this->load->view('templates/header', array('title' => 'Permission List'));
        $this->load->view('templates/echo', array('echo' => $echo));
        $this->load->view('templates/footer', array());
    }
    
    public function userjs(){
        if(!$this->users->loggedIn()) show_error("Not permitted", 400, "Authentification error");
        echo file_get_contents(APPPATH."/controllers/user.js");
    }
    
    public function user($id = null){
        if(!$this->permissions->hasPermission("list users")) show_error("Not permitted", 400, "Authentification error");
        $this->load->helper("image");
        if(!isset($id) || empty($id) || $id == null || !is_numeric($id)) show_error("Parameter has to exist an has to be a number.", 400, "Parameter Error");
        $user = $this->users->getUserByID($id);
        $echo = "";
        $echo .= "<h1>".$this->users->getUsername($user)."</h1><hr>";
        $echo .= "<style>";
        $echo .=    "
                    .permtable {
                        width: 100%;
                        border-collapse:collapse;
                        border-spacing:0;
                        border-color:#fff;
                    }
                     .permtable td{
                        font-family:Arial, sans-serif;
                        font-size:14px;
                        padding:10px 5px;
                        border-style:solid;
                        border-width:1px;
                        overflow:hidden;
                        word-break:normal;
                        border-color:#fff;
                        color:#333;
                        background-color:#fff;
                    }
                    .permtable tr{
                          -webkit-touch-callout: none; /* iOS Safari */
                            -webkit-user-select: none; /* Safari */
                             -khtml-user-select: none; /* Konqueror HTML */
                               -moz-user-select: none; /* Firefox */
                                -ms-user-select: none; /* Internet Explorer/Edge */
                                    user-select: none; /* Non-prefixed version, currently
                                                          supported by Chrome and Opera */
                    }
                    .permtable tr:hover:not(.nu){
                        text-decoration: underline;
                    }
                    .permtable tr:hover td{
                        background-color: #252525;
                    }
                     .permtable th{
                        font-family:Arial, sans-serif;
                        font-size:14px;
                        font-weight: bold;
                        padding:10px 5px;
                        border-style:solid;
                        border-width:1px;
                        overflow:hidden;
                        word-break:normal;
                        border-color:#fff;
                        color:#fff;
                        background-color:#006;
                    }
                     .permtable td{
                        background-color:#000;
                        color: #fff;
                    }
                    .btn {
                        color: white;
                        background-color: blue;
                        padding: 5px;
                        border-color: #fff;
                        border-style: solid;
                        border-width: 1px;
                    }
                    .fright {
                        float: right;
                    }
                    .fclear {
                        clear: both;
                    }
                    .access{
                        width: 35px;
                        height: 35px;
                    }
                    .access.none{
                        background-image: url('".base_url("permissionManager/keys/none")."');
                    }
                    .access.all{
                        background-image: url('".base_url("permissionManager/keys/all")."');
                    }
                    .access.inherit{
                        background-image: url('".base_url("permissionManager/keys/inherit")."');
                    }
                    ";
        $echo .= "</style>";
        $echo .= "<table class=\"permtable\">";
        $echo .= "    <tr class=\"nu\">";
        $echo .= "        <th>ID</th>";
        $echo .= "        <th>Name</th>";
        $echo .= "        <th>Slug</th>";
        $echo .= "        <th></th>";
        $echo .= "    </tr>";
        $tup = new Permissions($id);
        //print_r($tup);
        $uperm = $tup->getUserPermissions();
        $mperm = $this->permissions->getUserPermissions();
        //print_r($uperm);
        foreach($tup->getPermissions() as $permission){
            if(($tup->hasPermission($permission->id, $uperm)) && !($this->permissions->hasPermission('superuser'))) continue;
            if(!($this->permissions->hasPermission($permission->id, $mperm)) && !($this->permissions->hasPermission('superuser'))) continue;
            $echo .= "<tr>";
            $echo .= "<td>".$permission->id."</td>";
            $echo .= "<td>".$permission->name."</td>";
            $echo .= "<td>".$permission->slug."</td>";
            $echo .= "<td><div data-slug=\"".$permission->slug."\" data-id=\"".$permission->id."\" class=\"access ".(($this->permissions->hasPermission($permission->id, $uperm, null, false)) ? "all" : (($this->permissions->hasPermission($permission->id, $uperm, null, true)) ? "inherit" : "none"))."\"></div></td>";
            $echo .= "</tr>";
        }
        $echo .= "</table>";
        $domain = $_SERVER["SERVER_NAME"];
        $echo .= "<script>var managerurl=\"https://$domain".base_url("PermissionManager")."\"; var uid = \"$id\";</script>";
        $echo .= "<script src=\"".base_url("PermissionManager/userjs")."\"></script>";
        $this->load->view('templates/header', array('title' => 'Permission List'));
        $this->load->view('templates/echo', array('echo' => $echo));
        $this->load->view('templates/footer', array());
        $pms = $this->permissions->getPermissionsByLevel("superuser");
    }
    
    public function editpermission($uid = null, $permid = null, $active = null){
        $active = strtolower($active);
        if($active == "true") $active = true;
        else if($active == "false") $active = false;
        else $active = null;
        if($uid === null || $permid === null || $active === null || !is_numeric($uid) || !is_numeric($permid) || !is_bool($active)) echo json_encode(array('error' => 'Invalid data: User-ID: '.$uid." Permission-ID: $permid Activation: ".($active ? "true" : "false")));
        else if(!$this->permissions->hasPermission("superuser") && !$this->hasPermission($permid)) echo json_encode(array('error' => 'not permitted'));
        else{
            $this->permissions->setPermission($permid, $uid, $active);
        
            $res = array();
            $res["sid"] = $permid;
            $res["inherit"] = $this->permissions->getInheriting($permid, $uid);
            $res["active"] = $active;
            $res["inheriting"] = (new Permissions($uid))->hasPermission($permid);
            echo json_encode($res);
        }
    }
    
    public function edit($perm = null){
        $this->permissions->needPermission("edit_permission");
        if($perm === null) show_error("Invalid data", 400, "Parameter error");
        
        $p = $this->permissions->getPermission($perm);
        
        $this->formbuilder->setMethod("POST");
        $this->formbuilder->addField()->setTag("a")->setDescription("ID")->setInnerValue($p->id);
        $this->formbuilder->addField()->setTag("a")->setDescription("Name")->setInnerValue($p->name);
        $this->formbuilder->addField()->setTag("a")->setDescription("Slug")->setInnerValue($p->slug);
        $sel = $this->formbuilder->addField()->setTag("select")->setFillBothRows(true)->setName("needpermission")->setRule("required");
        $inheriting = $this->permissions->getInheriting($p->id);
        foreach($this->permissions->getPermissions() as $perm) {
            if(!in_array($perm->id, $inheriting)) {
                $f = $sel->addChild()->setTag('option')->setValue($perm->id)->setInnerValue($perm->name);
                if($p->need_for_access == $perm->slug) $f->setAttribute("selected", null);
            }
        }
        $this->formbuilder->addField()->setupSubmit("Edit Permission-Level")->setValue("edit")->setName("submid");
        if($p->slug != "superuser"){
            $this->formbuilder->addField()->setInnerValue("Delete Permission")->setTag("button")->setValue("delete")->setAttribute('onclick', 'showmodal()')->setFillBothRows(true)->setType("button");
        }
        $this->formbuilder->addField()->setTag("div")->setAttribute('id', 'myModal')->setClass('modal')->addChild()->setTag("div")->setClass('modal-content')->addChild()->setTag('span')->setClass('close')->setInnerValue('&times;')->parent()->addChild()->setTag('p')->setInnerValue('Enter the slug to confirm the deletion')->parent()->addChild()->setTag('input')->setAttribute('id', 'confirmation')->setAttribute('style', 'width: 100%;')->setAttribute('onkeydown', 'slugenter(this)')->setAttribute('onpaste', 'slugenter(this)')->setAttribute('oninput', 'slugenter(this)')->setInnerValue('<br><br>')->parent()->addChild()->setTag('button')->setType('button')->setAttribute('id', 'confirmationbutton')->setInnerValue('Delete Permission')->setAttribute('disabled', null)->setAttribute('onclick', 'deletepermission()');
        $this->formbuilder->addField()->setFillBothRows(true)->setTag('style')->setInnerValue('
#confirmationbutton {
  background: #ff0073;
  background-image: -webkit-linear-gradient(top, #ff0073, #a8004c);
  background-image: -moz-linear-gradient(top, #ff0073, #a8004c);
  background-image: -ms-linear-gradient(top, #ff0073, #a8004c);
  background-image: -o-linear-gradient(top, #ff0073, #a8004c);
  background-image: linear-gradient(to bottom, #ff0073, #a8004c);
  -webkit-border-radius: 28;
  -moz-border-radius: 28;
  border-radius: 28px;
  font-family: Arial;
  color: #ffffff;
  font-size: 20px;
  padding: 10px 20px 10px 20px;
  border: solid #3d3d3d 1px;
  text-decoration: none;
}
#confirmationbutton:hover {
  background: #ff00c4;
  background-image: -webkit-linear-gradient(top, #ff00c4, #a8009d);
  background-image: -moz-linear-gradient(top, #ff00c4, #a8009d);
  background-image: -ms-linear-gradient(top, #ff00c4, #a8009d);
  background-image: -o-linear-gradient(top, #ff00c4, #a8009d);
  background-image: linear-gradient(to bottom, #ff00c4, #a8009d);
  text-decoration: none;
}
#confirmationbutton:disabled {
  background: #e065a0;
  background-image: -webkit-linear-gradient(top, #e065a0, #8a3c61);
  background-image: -moz-linear-gradient(top, #e065a0, #8a3c61);
  background-image: -ms-linear-gradient(top, #e065a0, #8a3c61);
  background-image: -o-linear-gradient(top, #e065a0, #8a3c61);
  background-image: linear-gradient(to bottom, #e065a0, #8a3c61);
}
.modal {
    display: none; /* Hidden by default */
    position: fixed !important; /* Stay in place */
    z-index: 1 !important; /* Sit on top */
    padding-top: 100px !important; /* Location of the box */
    left: 0 !important;
    top: 0 !important;
    width: 100% !important; /* Full width */
    height: 100% !important; /* Full height */
    overflow: auto !important; /* Enable scroll if needed */
    background-color: rgb(0,0,0) !important; /* Fallback color */
    background-color: rgba(0,0,0,0.4) !important; /* Black w/ opacity */
    color: #000 !important;
}
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}
.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
        ');
        $this->formbuilder->addField()->setTag("script")->setInnerValue('
function slugenter(me){
    if(me.value == "'.$p->slug.'"){
        document.getElementById(\'confirmationbutton\').removeAttribute(\'disabled\');
    }
    else{
        document.getElementById(\'confirmationbutton\').setAttribute(\'disabled\', null);
    }
}

function deletepermission(){
    $(\'form\').append("<input type=\"hidden\" name=\"submid\" value=\"delete\">");
    document.getElementsByTagName("form")[0].submit();
}

var modal = document.getElementById(\'myModal\');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
function showmodal() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
document.addEventListener("DOMContentLoaded", function(event) {
		//alert(JSON.stringify($(\'form\').serialize()));
	});
        ');
        $this->formbuilder->setup();
        $this->load->view("templates/header", array('title' => $p->name));
        if($this->input->post("submit") == "edit"){
            if($this->formbuilder->validate()){
                if(in_array($this->input->post("needpermission"), $inheriting)){
                    $this->load->view("templates/echo", array('echo' => '<h1 style="color: red;">Error: Loops are forbidden.</h1>'));
                }
                else if($this->permissions->updatePermissionLevel($p->slug, $this->input->post("needpermission"))){
                    $this->load->view("templates/echo", array('echo' => '<h1>Updated successfully.</h1>'));
                }
                else{
                    $this->load->view("templates/echo", array('echo' => '<h1 style="color: red;">Error: Update failed.</h1>'));
                }
            }
        }
        else if($this->input->post("submid") == "delete"){
            if($this->permissions->deletePermission($p->id)){
                $noformshow = true;
                $this->load->view("templates/echo", array('echo' => '<h1>Error: Deleted Permission successfully.</h1>'));
            }
            else{
                $this->load->view("templates/echo", array('echo' => '<h1 style="color: red;">Error: Deletion failed.</h1>'));
            }
        }
        if(!isset($noformshow)) $this->load->view("templates/echo", array('echo' => $this->formbuilder->getForm()));
        $this->load->view("templates/footer", array());
    }
    
    public function enroll() {
        if(!$this->users->loggedIn()) show_error("Not permitted", 400, "Authentification error");
        $permissions = $this->permissions->getPermissions();
        $echo = "";
        $echo .= "<style>";
        $echo .=    "
                    .permtable {
                        width: 100%;
                        border-collapse:collapse;
                        border-spacing:0;
                        border-color:#fff;
                    }
                     .permtable td{
                        font-family:Arial, sans-serif;
                        font-size:14px;
                        padding:10px 5px;
                        border-style:solid;
                        border-width:1px;
                        overflow:hidden;
                        word-break:normal;
                        border-color:#fff;
                        color:#333;
                        background-color:#fff;
                    }
                    .permtable tr:hover:not(.nu){
                        text-decoration: underline;
                    }
                    .permtable tr:hover td{
                        background-color: #252525;
                    }
                     .permtable th{
                        font-family:Arial, sans-serif;
                        font-size:14px;
                        font-weight: bold;
                        padding:10px 5px;
                        border-style:solid;
                        border-width:1px;
                        overflow:hidden;
                        word-break:normal;
                        border-color:#fff;
                        color:#fff;
                        background-color:#006;
                    }
                     .permtable td{
                        background-color:#000;
                        color: #fff;
                    }
                    .btn {
                        color: white;
                        background-color: blue;
                        padding: 5px;
                        border-color: #fff;
                        border-style: solid;
                        border-width: 1px;
                    }
                    .fright {
                        float: right;
                    }
                    .fclear {
                        clear: both;
                    }
                    ";
        $echo .= "</style>";
        $echo .= "<table class=\"permtable\">";
        $echo .= "    <tr class=\"nu\">";
        $echo .= "        <th>ID</th>";
        $echo .= "        <th>Name</th>";
        $echo .= "        <th>Slug</th>";
        $echo .= "        <th>Level</th>";
        $echo .= "    </tr>";
        $uperm = $this->permissions->getUserPermissions();
        foreach ($permissions as $permission) {
            if(!$this->permissions->hasPermission($permission->id, $uperm)) continue;
            $echo .= "<tr class=\"href\" data-href=\"".base_url("PermissionManager/edit/".$permission->id)."\">";
            $echo .= "<td>".$permission->id."</td>";
            $echo .= "<td>".$permission->name."</td>";
            $echo .= "<td>".$permission->slug."</td>";
            $echo .= "<td>".$permission->need_for_access."</td>";
            $echo .= "</tr>";
        }
        $echo .= "</table>";
        $echo .= "<script>
                    $(\"tnoscript\").remove();
                    $(\".href\").click(function() {
                        window.location = $(this).data(\"href\");
                    });
                  </script>";
        $echo .= "<br><a class=\"btn fright\" href=\"".base_url("permissionManager/add")."\">Add Permission-Entry</a><div class=\"fclear\"></div>";
        $this->load->view('templates/header', array('title' => 'Permission List'));
        $this->load->view('templates/echo', array('echo' => $echo));
        $this->load->view('templates/footer', array());
    }
    
    public function add(){
        $this->lusers->needLogin();
            $this->permissions->needPermission('add_permission');
            echo $this->input->post('title');
            $data['title'] = 'Create a news item';
        
            $this->form_validation->set_rules('name', 'Text', 'required');
        
            if ($this->form_validation->run() === FALSE)
            {
                $this->load->view('templates/header', $data);
                $this->formbuilder->setMethod("POST");
                $this->formbuilder->addField((new Field())->setType("text")->setName("name")->setDescription("Name"));
                $sel = $this->formbuilder->addField()->setTag("select")->setFillBothRows(true)->setName("needpermission");
                foreach($this->permissions->getPermissions() as $perm){
                    $sel->addChild()->setTag('option')->setValue($perm->slug)->setInnerValue($perm->name);
                }
                $this->formbuilder->addField((new Field())->setType("submit")->setName("submit")->setValue("Create Permission")->setFillBothRows(true));
                $this->load->view('templates/echo', array('echo' => $this->formbuilder->getForm()));
                $this->load->view('templates/footer');
        
            }
            else
            {
                $name = $this->input->post('name');
                $need = $this->input->post('needpermission');
                if(!$this->permissions->existsPermission($this->permissions->slugify($name))){
                    $this->permissions->addPermission($name, $this->permissions->slugify($need));
                }
                else{
                    show_error("Permission already exists.", 406, "Permission Insert Error");
                }
            }
        
    }
}