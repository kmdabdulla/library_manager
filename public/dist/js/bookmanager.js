$("#changeBookStatusButton").click(function(){
    var data = $(this).data('info').split(',');
    $("#bookId").val(data[0]);
    $("#bookName").text(data[1]);
    $('#changeBookStatusModal').modal('show');
  });
