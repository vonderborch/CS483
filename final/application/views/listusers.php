<div id="body-listusers-body">
    <h3><?php echo anchor("Admin/AddUser/", 'Add New User'); ?> </h3>

    <h3>Users</h3>

    <p>
    <?php foreach($users as $user) : ?>
    
        - <?php echo $user->username; ?>  :  
        <?php echo anchor("Admin/EditUser/{$user->id}", 'Edit User'); ?>
        <?php echo anchor("Admin/DeleteUser/{$user->id}", 'Delete User', array('onclick' => "return confirm('Are you sure want to delete this user?')")); ?>
    
        </br>
    <?php endforeach; ?>
    </p>

</div>