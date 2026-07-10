$(document).ready(function() {

    $('.select2').select2({
        placeholder: 'Departamento',
        allowClear: true,
        width: '100%',
    });

    $('.select2area').select2({
        placeholder: 'Area',
        allowClear: true,
        width: '100%',
    });

    $('.select2general').select2({
        placeholder: 'General',
        allowClear: true,
        width: '100%',
    });

    $('#department').on('change', function() {
        var department = $(this).val();
        if (department) {
            showOrganization(department, null, null);
        }
        else {
            showOrganization();
        }
    });

    $('#area').on('change', function() {
        var area = $(this).val();
        if (area) {
            showOrganization(null, area, null);
        }
        else {
            showOrganization();
        }
    });

    $('#general').on('change', function() {
        var general = $(this).val();
        if (general) {
            showOrganization(null, null, general);
        }
        else {
            showOrganization();
        }
    });

    // Inicializa el organigrama
    function showOrganization( department = null, area = null, general = null) {

        let option;
        if(department != null ){
            option = 'department'
        }
        if(area != null){
            option = 'area'
        }
        if(general != null){
            option = 'general'
        }

        switch (option) {
            case 'department':
                var api_url = base_url + 'organization/data/department/' + department;
                break;
            case 'area':
                var api_url = base_url + 'organization/data/area/' + area;
                break;
            case 'general':
                var api_url = base_url + 'organization/data/general/' + general;
                break;
            default:
                var api_url = base_url + 'organization/data';
        }
                
        $.ajax({
            'url': api_url,
            'dataType': 'json'
        })
        .done(function(data) {
            console.log('me llame')
            datascource = data;
            $('#chart-container').empty();
            $('#chart-container').orgchart({
                'data': data,
                'nodeTitle': 'title',
                'nodeContent': 'name',
                'createNode': function($node, data) {
                    var titleText = (data && typeof data.title !== 'undefined' && data.title !== null) ? String(data.title).trim() : '';
                    if (!titleText || titleText.toLowerCase() === 'null') {
                        if (typeof data.pid === 'undefined' || data.pid === null || data.pid === 0) {
                            $node.find('.title').text('Trantor Technologies');
                        } else {
                            $node.find('.title').text('');
                        }
                    }
                    if(data.ghost) {
                        $node.addClass('ghost__node');
                        if (data.pid == 10000001) {
                            $node.addClass('ghost__node__root');
                        } else {
                            $node.addClass('ghost__node__niveles__' + data.niveles);
                        }
                    }else{
                        $node.addClass('normal__node');
                    }
                }
            });
        });
    }

    // Despliegue inicial
    showOrganization();
});