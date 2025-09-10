    $(document).ready(function () {
      $('#tableBioUBS').DataTable(
            {

            dom: 'Bfrtip',
              "buttons": [
                  //'copy', 'csv', 'excel', 'pdf', 'print'
                  'excel', 'print'
                  ],
            }
        );
    });
