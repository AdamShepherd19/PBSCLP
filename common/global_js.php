<?php
    session_start();
?>

<script>
    $(document).ready(function () {
        var accountType = '<?php echo $_SESSION['account_type']; ?>';
        if (accountType != 'administrator') {
            $('.admin-only').hide();
        } else {
            $('.admin-only').show();
        }
    });
</script>