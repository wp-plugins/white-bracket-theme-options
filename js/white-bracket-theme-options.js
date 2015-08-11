jQuery(document).ready(function($) {
    var wrapper         = $(".add-new-option-container"); //Fields wrapper
    var add_button      = $(".add-more-options"); //Add button ID
    var option_type     = 'input';
    var option_id       = 1;

    $('select[name="option-type"]').change(function(){
        option_type = $(this).val();
    });
    
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(option_type == 'input'){
            $(wrapper).append('<div class="row"><div class="option-title"><input type="text" name="option'+option_id+'[]" /></div><div class="option-type"><input type="text" name="option'+option_id+'[]" value="'+option_type+'" readonly="readonly" /></div><div class="option-value"><input type="text" name="option'+option_id+'[]" /></div><a href="#" class="remove-option">Remove</a></div></div>');
            option_id++;
        } else if( option_type == 'wysiwyg' ){
            $(wrapper).append('<div class="row"><div class="option-title"><input type="text" name="option'+option_id+'[]" /></div><div class="option-type"><input type="text" name="option'+option_id+'[]" value="'+option_type+'" readonly="readonly" /></div><div class="option-value"><textarea placeholder="Will become a WYSIWYG editor upon saving." name="option'+option_id+'[]"></textarea></div><a href="#" class="remove-option">Remove</a></div></div>');
            option_id++;
        }
    });
    
    $(wrapper).on("click",".remove-option", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove();
    })

    $('.success').delay(2500).slideUp();

    $('.toggle-content').click(function(){
        $(this).parent().siblings('.row-content').slideToggle();
        $(this).parent().toggleClass('active');
    });
});