<?php

namespace Drupal\powerbi\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class PowerbiSettingsForm extends ConfigFormBase {

  /*
   * {@inheritdoc}
   */
    public function getFormId() {
        return 'powerbi_settings_form';
    }
    /*
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() {
        return ['powerbi.settings'];
    }
    /*
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form = parent::buildForm($form, $form_state);
        $config = $this->config('powerbi.settings');

        $form['powerbi'] = array(
            '#type' => 'fieldset',
            '#title' => $this->t('Power Bi Settings'),
        );
        $form['powerbi']['baseurl'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Power bi url'),
            '#default_value' => $config->get('powerbi.baseurl'),
            '#required' => TRUE,
        );
        $form['powerbi']['grant_type'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Grant type'),
            '#default_value' => $config->get('powerbi.grant_type'),
            '#required' => TRUE,
        );
        $form['powerbi']['client_id'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Client ID'),
            '#default_value' => $config->get('powerbi.client_id'),
            '#required' => TRUE,
        );
        $form['powerbi']['client_secret'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Power bi secret'),
            '#default_value' => $config->get('powerbi.client_secret'),
            '#required' => TRUE,
        );
        $form['powerbi']['token_endpoint'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Token end point'),
            '#default_value' => $config->get('powerbi.token_endpoint'),
            '#required' => TRUE,
        );
        return $form;
    }
    public function submitForm(array &$form, FormStateInterface $form_state){
        $config = $this->config('powerbi.settings');
        $config->set('powerbi.baseurl', $form_state->getValue('baseurl'));
        $config->set('powerbi.grant_type', $form_state->getValue('grant_type'));
        $config->set('powerbi.client_id', $form_state->getValue('client_id'));
        $config->set('powerbi.client_secret', $form_state->getValue('client_secret'));
        $config->set('powerbi.token_endpoint', $form_state->getValue('token_endpoint'));
        $config->save();
    }
}