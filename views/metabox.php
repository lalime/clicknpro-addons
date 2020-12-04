<?php
?>
<label for="clicknpro_field" style="margin-bottom:15px;">Accessible à </label><br>
<input type="radio" id="clicknpro_all" name="is_clicknpro_private" value="0" <?php echo !$isClicknProPrivate ? 'checked' : '' ?>>
<label for="clicknpro_all">Tout le monde</label><br>
<input type="radio" id="clicknpro_restricted" name="is_clicknpro_private" value="1" <?php echo $isClicknProPrivate ? 'checked' : '' ?>>
<label for="clicknpro_restricted">Abonnés uniquement</label><br>