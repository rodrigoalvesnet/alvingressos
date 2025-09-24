<div class="myk-wa">
    <?php
    $contacts = Configure::read('Whatsapp.contacts');
    foreach ($contacts as $contact) {
    ?>
        <div class="myk-item" 
        data-wanumber="<?php echo $contact['number']; ?>" 
        data-waname="<?php echo $contact['subtitle']; ?>" 
        data-wadivision="<?php echo $contact['title']; ?>" 
        data-waava="<?php echo $contact['image']; ?>"
        data-watext="<?php echo $contact['text']; ?>"
        ></div>
    <?php } ?>
    <script>
        $(function() {
            $(".myk-wa").WAFloatBox();
        });
    </script>
</div>