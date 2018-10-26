<div class="wrap">
    <h1><?php _e('Update Employee', $this->text_domain); ?></h1>

    <br>
    <img src="<?php echo wp_get_attachment_image_url($entry->avatar) ?>" alt="" width="150">

    <form method="post" enctype="multipart/form-data">

        <table class="form-table">
            <tbody>
                <tr class="row-name">
                    <th scope="row">
                        <label for="name"><?php _e('Name', $this->text_domain); ?></label>
                    </th>
                    <td>
                        <input type="text" name="name" id="name" class="regular-text" placeholder="<?php echo esc_attr('', $this->text_domain); ?>" value="<?php echo esc_attr($entry->name); ?>" required="required" />
                    </td>
                </tr>
                <tr class="row-occupation">
                    <th scope="row">
                        <label for="occupation"><?php _e('Occupation', $this->text_domain); ?></label>
                    </th>
                    <td>
                        <input type="text" name="occupation" id="occupation" class="regular-text" placeholder="<?php echo esc_attr('', $this->text_domain); ?>" value="<?php echo esc_attr($entry->occupation); ?>" required="required" />
                    </td>
                </tr>
                <tr class="row-email">
                    <th scope="row">
                        <label for="email"><?php _e('Email Address', $this->text_domain); ?></label>
                    </th>
                    <td>
                        <input type="email" name="email" id="email" class="regular-text" placeholder="<?php echo esc_attr('', $this->text_domain); ?>" value="<?php echo esc_attr($entry->email); ?>" required="required" />
                    </td>
                </tr>
                <tr class="row-avatar">
                    <th scope="row">
                        <label for="avatar"><?php _e('Avatar', $this->text_domain); ?></label>
                    </th>
                    <td>
                        <input type="file" name="avatar" id="avatar" accept="image/*"/>
                    </td>
                </tr>
             </tbody>
        </table>

        <input type="hidden" name="field_id" value="<?php echo $entry->id; ?>">

        <?php wp_nonce_field(); ?>
        <?php submit_button(__('Update', $this->text_domain), 'primary', 'submit_employees'); ?>

    </form>
</div>