$('#add-image').click(function(){
    //Je récupère le numéro des futurs champs
    const index = +$('#widget-counter').val();

    //Je récupère le prototype des entrées
    const tmpl = $('#annonce_images').data('prototype').replace(/__name__/g, index);

    //J'injecte ce code au sein de la div
    $('#annonce_images').append(tmpl);

    $('#widget-counter').val(index+1);

    //Je gère le boutton supprimer
    handleDeleteButtons();
});

function handleDeleteButtons(){
    $('button[data-action="delete"]').click(function () {
        const target = this.dataset.target;
        $(target).remove();
    });
}

function updateCounter(){
    const count = +$('#annonce_images div.form-group').length;

    $('#widget-counter').val(count);
}

updateCounter();
handleDeleteButtons();