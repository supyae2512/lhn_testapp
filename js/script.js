$(document).ready(() => {
    const host = window.location.origin;
    // List View 
    loadData({}, 'GET')
        .done((response) => {
            if (response && response.data.length > 0) {
                var content = "";
                $.each(response.data, (i, val) => {
                    var status = val.status == 1 ? 'Done' : 'Pending'
                    content += `<tr><td> ${val.id}</td>
                        <td>${val.title}</td>
                        <td>${val.assignee}</td>
                        <td>${status}</td>
                        <td><a href="#" class="btn btn-outline-dark insert" data-id=${val.id}>
                        <i class="fa fa-edit"></i>
                        </a></td><tr>`;
                });
                var viewPath = "views/list.html";
                $.get(viewPath, (data) => {
                    $('#data-container').html(data)
                })
                .done(response => {
                    $('.task-list')
                    .html(content);
                });               
            }
        })
        .fail((error) => {
            console.error(error);
        });

    // Add or Update
    $('#data-container').on('click', '.insert', function(e) {
        e.preventDefault();
        var formPath = "views/form.html";
        const id = $(this).data('id');
        $.get(formPath, (data) => {
            $('#data-container').html(data);
        })
        .done((response) => {
            if (id != '' && id !== undefined) {
                loadData({}, "GET", `/tasks?id=${id}`)
                .done((response) => {

                    $('#id').val(response.data.id);
                    $('#title').val(response.data.title);
                    $('#assignee').val(response.data.assignee);
                    $('#description').val(response.data.description);
                    if (response.data.status == 1) $('input[name="status"][value=1]').prop('checked', true);
                    else $('input[name="status"][value=0]').prop('checked', true);
                })
                .fail((error) => {
                    console.error(error);
                });
            } else {
                $('input[name="status"][value=0]').prop('checked', true);
                console.log('new form');
            }
        });
    });

    $('#data-container').on('click', '#addNew', function(e) {
        e.preventDefault();
        if ($.trim($('#title').val()) == '' || $.trim($('#assignee').val()) == '') {
            $('.invalid-feedback').css('display', 'block');
            return;
        } 
        var data = $('.newForm').serializeArray();
        const isUpdate = $('#id').val();
        var input={};
        $.each(data, (i, val) => {
            input[val.name] = val.value;
        });
        if (isUpdate !== 'undefined' && isUpdate !== '') {
            loadData(JSON.stringify(input), 'PUT'); 
        }
        else loadData(input, 'POST'); 
        location.reload();   
    });
});

function loadData(inputData={}, requestMethod='POST', requestUrl = '/tasks') {
    const host = window.location.origin;
    const APIRoute = host + requestUrl;
    return $.ajax({
            url: APIRoute, 
            method: requestMethod,
            data: inputData
        });
}