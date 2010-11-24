<?PHP

require_once('Widget.admin.php');
require_once('../placeholder.php');

############################################
# Class SmsNotify
############################################
class SmsNotify extends Widget
{
    var $sms_params = array('sms_enabled', 'sms_username', 'sms_apikey', 'sms_phones', 'sms_sender');

    function SmsNotify(&$parent)
    {
        Widget::Widget($parent);
        $this->prepare();
    }

    function prepare()
    {
        if(isset($_POST['sms']))
        {
            if(empty($_POST['token']) || $_POST['token'] !== $_SESSION['token'])
            {
                header('Location: http://'.$this->root_url.'/admin/');
                exit();
            }

            $settings = $_POST['sms'];

            if (isset($settings['sms_enabled']) && $settings['sms_enabled'])
                $settings['sms_enabled'] = 1;
            else
                $settings['sms_enabled'] = 0;

            foreach ($settings as $name => $value) {
                if (in_array($name, $this->sms_params)) {
                    $this->db->query("update `settings` set `value` = '$value' where `name` = '$name'");
                }
            }

            header('Location: index.php?section=SmsNotify');
        }
    }

    function fetch()
    {
        $this->title = 'Настройка SMS-уведомлений';

        $this->smarty->assign('Settings', $this->settings);
        $this->smarty->assign('ErrorMSG', $this->error_msg);
        $this->smarty->assign('Lang', $this->lang);
        $this->body = $this->smarty->fetch('sms_notify.tpl');
    }
}