<div class="wrap">
    <h2>
        <?php _e('Employees', $this->text_domain); ?> 
        <a href="<?php echo admin_url('admin.php?page=employees&action=new'); ?>" class="add-new-h2">
            <?php _e('Add New', $this->text_domain); ?>
        </a>
    </h2>

    <form method="post">
        <input type="hidden" name="page" value="employees">
        <?php $list_table->display(); ?>
    </form>
</div>