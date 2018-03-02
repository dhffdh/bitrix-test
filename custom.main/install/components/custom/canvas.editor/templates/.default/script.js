$(document).ready(function () {


    var canvas = new fabric.Canvas('js-canvas-container', {
        isDrawingMode: true
    });
    canvas.freeDrawingBrush.width = 20;


    var $canvas = $('#js-canvas-container'),
        $saveBtn = $('#js-canvas-save'),
        $clearBtn = $('#js-canvas-clear'),
        $form = $('#js-canvas-form');


    if(!!$canvas.data('image') && $canvas.data('image').length > 0){

        fabric.Image.fromURL($canvas.data('image'), function(myImg) {
            var img1 = myImg.set({ left: 0, top: 0 ,width:600,height:400});
            canvas.add(img1);
        });

    }

    $clearBtn.on('click', function() {
        canvas.clear();
    });


    $form.on('submit', function(e) {
        e.preventDefault();

        var base64 = canvas.toDataURL(),
            btnText = $saveBtn.text(),
            formData = $form.serializeArray();

        $saveBtn.attr('disabled','1').text('...');

        formData.push({name: 'base64', value: base64});

        //console.log('formData',formData);

        $.ajax({
            type: "POST",
            url: "/local/components/custom/canvas.editor/ajax.php",
            data: formData,
            dataType: 'json',
            success: function(data){

                $form[0].reset();

                if(!!data.ERROR){
                    alert(data.ERROR_MESSAGE)
                }
                if(!!data.SUCCESS){
                    alert(data.SUCCESS_MESSAGE)
                }
            },
            complete: function () {
                $saveBtn.removeAttr('disabled').text(btnText);
            }
        });

    });

});