jQuery(document).ready(function(){
    jQuery('input[name="rltags"]').on('blur', function(){
        var tagInput = jQuery(this);
        var rltags = tagInput.val();
        var totalWords = rltags.split(",");
        // var totalAllowedTags = 4;

        jQuery(document).find('.max_tags_msg').remove();

        console.log(rltags);

        if(totalWords.length > totalAllowedTags ) {
            tagInput.css('border', '1px solid red');
            let message = '<p class="max_tags_msg"style="color:red;">Vous ne pouvez renseigner que mots '+ totalAllowedTags +' clés au plus.</p>';
            tagInput.closest('.form-group').append(message);
            jQuery('input[name="rlsubmit"]').prop('disabled', true);
        } else {
            tagInput.css('border', '1px solid rgb(235 237 245)');
            jQuery('input[name="rlsubmit"]').prop('disabled', false);
        }
    });
});