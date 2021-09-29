<?php

include_once 'm/User.php';

abstract class C_base extends C_controller
{
    protected $title;
    protected $content;

    function __construct() {
    }

    protected function before()
    {
        $this->title = 'Shop';
        $this->content = '';
    }

    public function render()
    {
        $get_user = new User();
        if (isset($_SESSION['user_id'])) {
            $user_info = $get_user->get($_SESSION['user_id']);
        } else {
            $user_info['name'] = false;
        }
        $vars = array('title' => $this->title, 'content' => $this->content, 'user' => $user_info['name']);
        $page = $this->Template('v/v_main.php', $vars);
        echo $page;
    }
//относится к корзине
    public function action_index(){
        $this->title .= '::Каталог';
        $goods = new Goods();
        $goods_arr = $goods->getCatalog();
        $this->content = $this->Template('v/v_index.php', array('items' => $goods_arr));
        if($this->isPost()) {
           /* какой то код */
        }
    }

    public function action_item() {
        $goods = new Goods();
        $good = $goods->getGood($_GET['id']);
        $this->title .= '::'.$good['title'];
        $this->content = $this->Template('v/goods_item.php', array('item' => $good));
        // добавляем количество просмотров к товару и к юзеру

        $goods->addView($_GET['id']);
    }
    public function action_photo() {
        $goods = new Goods();
        $good = $goods->getGood($_GET['id']);
        $this->title .= '::'.$good['title'];
        $this->content = $this->Template('v/photo.php', array('text' => $good['photo']));


    }

//относится к юзеру
    public function action_info() {
        $get_user = new User();
        $user_info = $get_user->get($_SESSION['user_id']);
        $this->title .= '::' . $user_info['name'];
        $this->content = $this->Template('v/user_info.php', array('username' => $user_info['name'], 'userlogin' => $user_info['login']));
    }

    public function action_reg() {
        $this->title .= '::Регистрация';
        $this->content = $this->Template('v/user_reg.php', array());

        if($this->isPost()) {
            $new_user = new User();
            $result = $new_user->newR($_POST['name'], $_POST['login'], $_POST['password']);
            $this->content = $this->Template('v/user_reg.php', array('text' => $result));
        }
    }

    public function action_login() {
        $this->title .= '::Вход';
        $this->content = $this->Template('v/user_login.php', array());

        if($this->isPost()) {
            $login = new User();
            $result = $login->login($_POST['login'], $_POST['password']);
            $this->content = $this->Template('v/user_login.php', array('text' => $result));
        }
    }

    public function action_logout() {
        $logout = new User();
        $result = $logout->logout();
    }
}
?>