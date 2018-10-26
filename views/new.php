<div class="wrap">
    <h1><?php _e('Add New Employee', $this->text_domain); ?></h1>

    <form method="post" enctype="multipart/form-data">

        <table class="form-table">
            <tbody>
                <tr class="row-name">
                    <th scope="row">
                        <label for="name"><?php _e('Name', $this->text_domain); ?></label>
                    </th>
                    <td>
                        <input type="text" name="name" id="name" class="regular-text" placeholder="<?php echo esc_attr('', $this->text_domain); ?>" required="required" />
                    </td>
                </tr>
                <tr class="row-occupation">
                    <th scope="row">
                        <label for="occupation"><?php _e('Occupation', $this->text_domain); ?></label>
                    </th>
                    <td>
                        <input type="text" name="occupation" id="occupation" class="regular-text" placeholder="<?php echo esc_attr('', $this->text_domain); ?>" required="required" />
                    </td>
                </tr>
                <tr class="row-email">
                    <th scope="row">
                        <label for="email"><?php _e('Email Address', $this->text_domain); ?></label>
                    </th>
                    <td>
                        <input type="email" name="email" id="email" class="regular-text" placeholder="<?php echo esc_attr('', $this->text_domain); ?>" required="required" />
                    </td>
                </tr>
                <tr class="row-avatar">
                    <th scope="row">
                        <label for="avatar"><?php _e('Avatar', $this->text_domain); ?></label>
                    </th>
                    <td>
                        <input type="file" name="avatar" id="avatar" required="required" accept="image/*" />
                    </td>
                </tr>
             </tbody>
        </table>

        <input type="hidden" name="field_id" value="0">

        <?php wp_nonce_field(); ?>
        <?php submit_button(__('Add New', $this->text_domain), 'primary', 'submit_employees'); ?>

    </form>
</div>