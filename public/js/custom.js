$(document).ready(function() {
    $('#status-list-data-table').DataTable( {
      "language": {
            "emptyTable":     "No active users/machines found!"
      },
      "serverSide": true,
      "bProcessing": true,
      "ajax":{
          url :"online/page",
          type: "post",
          'headers': {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          error: function(){ 
            
          }
      },
      "order": [[ 3, "desc" ]],
      "columns": [
            //{ "data": "id" },
            { "data": "name" },
            { "data": "email" },
            { "data": "ip" },
            { "data": "start" }
        ]
    });

    $('#logs-list-data-table').DataTable( {
        dom: 'Bfrtip',
        buttons: [
          //  'csv', 'excel', 'pdf'
        ],
        "language": {
            "emptyTable":     "No user logs found!"
        },
        "serverSide": true,
        "bProcessing": true,
        "ajax":{
            url :"logs/page",
            type: "post",
            'headers': {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            error: function(){ 
              
            }
        },
        "order": [[ 3, "desc" ]],
        "columns": [
              //{ "data": "id" },
              { "data": "name" },
              { "data": "email" },
              { "data": "log" },
              { "data": "created_at" },
              { "data": "timestamp" }
          ]
    } );

    $('#logs-list-data-table-form input[type="search"]').on('input propertychange paste', function() {
        $('#searchvalue').val($(this).val());
    });

    var nodeTable = $('#nodes-list-data-table').DataTable( {
      "language": {
            "emptyTable":     "No nodes found!"
      },
      "serverSide": true,
      "bProcessing": true,
      "ajax":{
          url :"nodes/page",
          type: "post",
          'headers': {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          error: function(){ 
            
          }
      },
      "order": [[ 0, "desc" ]],
      "columns": [
            //{ "data": "id" },
            { "data": "ip" },
            { "data": "actions" }
        ]
    });

    $('#nodes-list-data-table tbody').on( 'click', '.delete-node', function () {
      var confirmDelete = confirm("Are you sure you want to delete this node?");
      if (confirmDelete) {
        var idInfo = $(this).parents('tr').attr('id');
        var idArr = idInfo.split('_');
        //@todo - ajax delete
         $.ajax({
            url :'nodes/delete',
            type: 'post',
            data: {id: idArr[1]},
            dataType: 'json',
            'headers': {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(result) { 
              if (result.status='success') {
                alert(result.message);
                userTable.row($(this).parents('tr')).remove().draw(false);
              }
            }
        });
      }
      
    });

    $(document).on('click', '.delete-confirm-node', function () {
         var rowId = $(this).parents('tr').attr('id');
         $('#nodeConfirmModal').data('id', rowId );
         $('#nodeConfirmModal').modal('show');
    });

    $(document).on('click', '#delete-node', function () {
        var rowId = $('#nodeConfirmModal').data('id');
        var idArr = rowId.split('_');

        $('#nodeConfirmModal').modal('hide');
        //@todo - ajax delete
        $.ajax({
            url :'nodes/delete',
            type: 'post',
            data: {id: idArr[1]},
            dataType: 'json',
            'headers': {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(result) { 
              if (result.status='success') {
                //alert(result.message);
                //$('.animated.fadeIn').prepend('<div class="alert alert-success">'+result.message+'</div>');
                showAlertBox(result.message, 'success');
                nodeTable.row('#'+rowId).remove().draw(false);
              }
            }
        });
    });

    var userTable = $('#users-list-data-table').DataTable( {
        "language": {
            "emptyTable":     "No users found!"
        },
        "serverSide": true,
        "bProcessing": true,
        "ajax":{
            url :"users/page",
            type: "post",
            'headers': {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            error: function(){ 
              
            }
        },
        columnDefs: [
           { orderable: false, targets: -1 }
        ],
        /*'columnDefs': [{
             'targets': 0,
             'searchable':false,
             'orderable':false,
             'className': 'dt-body-center',
             'render': function (data, type, full, meta){
                 return '<input type="checkbox" name="media[]" value="' 
                    + $('<div/>').text(data).html() + '">';
             }
          }],*/
        "aaSorting": [[ 5, "desc" ]],
        "columns": [
                //{ "data": "id" },
                { "data": "firstname" },
                { "data": "lastname" },
                { "data": "username" },
                { "data": "email" },
                { "data": "loginlimit" },
                { "data": "created_at" },
                //{ "data": "type" },
                //{ "data": "status" },
                { "data": "actions" }
            ]
		/*"aoColumns": [
			{ mData: 'Name' } ,
		    { mData: 'Email' },
		    { mData: 'Created' },
		    { mData: 'Action' }
		]*/
    } );

    $(document).on('click', '.delete-confirm-user', function () {
         var rowId = $(this).parents('tr').attr('id');
         $('#userConfirmModal').data('id', rowId );
         $('#userConfirmModal').modal('show');
    });

    $(document).on('click', '#delete-user', function () {
        var rowId = $('#userConfirmModal').data('id');
        var idArr = rowId.split('_');

        $('#userConfirmModal').modal('hide');
        //@todo - ajax delete
        $.ajax({
            url :'users/delete',
            type: 'post',
            data: {id: idArr[1]},
            dataType: 'json',
            'headers': {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(result) { 
              if (result.status='success') {
                //alert(result.message);
                //$('.animated.fadeIn').prepend('<div class="alert alert-success">'+result.message+'</div>');
                showAlertBox(result.message, 'success');
                userTable.row('#'+rowId).remove().draw(false);
              }
            }
        });
    });

    function showAlertBox(message, type) {
      type = type || 'success';
      $('.alert').remove();
      $('.animated.fadeIn').prepend('<div class="alert alert-'+type+'">'+message+'</div>');
      closeAlertBox();
    }

    function closeAlertBox() {
      $('.alert-success, .alert-danger').fadeTo(2000, 500).slideUp(500, function(){
          $('.alert-success, .alert-danger').html('').slideUp(500);
      });
    }
    closeAlertBox();
} );