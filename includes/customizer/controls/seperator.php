<?php
if ( ! class_exists( 'WP_Customize_Control' ) )
return NULL;

class Seperator_Custom_Control extends WP_Customize_Control {

    public $display = 'Paragraaf X';

    public function render_content() {
        ?>
        <div style="padding: 8px 12px; background-color: #C67DFF; color: #fff; font-size: 1.3em; border-radius: 4px; border-bottom: 4px solid #7958B3; border-top: 4px solid #7958B3;">
            <b><?= $this->display ?></b>
        </div>
        <?php
    }
}