$(document).ready(function(){
    
    //$(document).on('click', '.option_word_radio', function(){
    $('.option_word_radio').click(function(){
        //alert("lol");
        $(this).siblings('.option_word').removeClass('correct_option');
        $(this).siblings('.option_word').removeClass('wrong_option');
        
        var correct_opt = $(this).siblings('input[name="correctAnswer"]').val();
        
        $(this).siblings('.option_word[data-value='+correct_opt+']').addClass('correct_option');
        //alert("Correct: "+correct_opt+"\nAnswer: "+$(this).val());
        
        if($(this).val() != correct_opt)
        {
            $(this).siblings('.option_word[data-value='+$(this).val()+']').addClass('wrong_option');
            $(this).parents('td').css('background-color', '#d2513c61');
        }
        else
            $(this).parents('td').css('background-color', '#3ed23c61');
            
            $(this).siblings('input').attr('disabled','disabled');
        
    });
    
});