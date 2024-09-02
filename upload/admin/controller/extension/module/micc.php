<?php
/**
 * @property \Admin\ModelCatalogAttribute $model_catalog_attribute
 * @property \Admin\ModelCatalogCategory $model_catalog_category
 * @property \Admin\ModelSettingSetting $model_setting_setting
 * @property \Admin\ModelToolImage $model_tool_image
 */
class ControllerExtensionModuleMicc extends Controller {
    public function index(){
        $this->load->language('extension/module/micc');
        $this->document->setTitle($this->language->get('page_title'));

        $this->load->model('extension/module/micc');
        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()))
        {
            $post = $this->request->post;
            $setting = [];
            $setting['micc_status_img'] = $post['micc_status_img'];
            $setting['micc_status_cats'] = $post['micc_status_cats'];
            $setting['micc_status'] = $post['micc_status'];
            $i = 0;
            unset($post['micc_status_img']);
            unset($post['micc_status_cats']);
            unset($post['micc_status']);

            $simple_array = [];
            foreach($post as $d)
            {
                $simple_array[]=$d;
            }
            $this->model_extension_module_micc->update($simple_array);

            $this->model_setting_setting->editSetting('micc', $setting);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true));
        }

        $data = [];

        $data['heading_title'] = $this->language->get('heading_title');
        $data['button_save'] = $this->language->get('button_save');
        $data['settings_edit'] = $this->language->get('settings_edit');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['manufacturers'] = $this->model_extension_module_micc->getAll();

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('page_title'),
            'href' => $this->url->link('extension/module/micc', 'user_token=' . $this->session->data['user_token'], true)
        );

        if (isset($this->request->post['micc_status'])) {
            $data['status'] = $this->request->post['micc_status'];
        } else {
            $data['status'] = $this->config->get('micc_status');
        }

        if (isset($this->request->post['micc_status_cats'])) {
            $data['status_cats'] = $this->request->post['micc_status_cats'];
        } else {
            $data['status_cats'] = $this->config->get('micc_status_cats');
        }

        if (isset($this->request->post['micc_status_img'])) {
            $data['status_img'] = $this->request->post['micc_status_img'];
        } else {
            $data['status_img'] = $this->config->get('micc_status_img');
        }

        $data['action'] = $this->url->link('extension/module/micc', 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view('extension/module/micc', $data));
    }

    public function install(){
        $this->load->model('setting/setting');

        $setting['micc_status'] = 0;
        $setting['micc_status_cats'] = 0;
        $setting['micc_status_img'] = 0;

        $this->model_setting_setting->editSetting('micc', $setting);

        $this->load->model('extension/module/micc');
        $this->model_extension_module_micc->install();
    }

    public function uninstall(){
        $this->load->model('setting/setting');

        $this->model_setting_setting->deleteSetting('micc');

        $this->load->model('extension/module/micc');
        $this->model_extension_module_micc->uninstall();
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/micc')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}