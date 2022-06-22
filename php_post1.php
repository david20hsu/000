<!DOCTYPE html>
<html>
  <head>
    <title></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <style>
    </style>
  </head>
  <body>
  <div id="result">This is a div container</div>
  <input type="button" id="load" value="load">
  <script>   
    $(function(){
      $("#load").click(function() {
        $.ajax({
          type: "POST", 
          url: "test.php?user=tony", 
          dataType: "html",
          data: {pwd: 123},
          success: function(data) {
            $("#result").html(data);
            },
          error: function(xhr) {
            alert(xhr.status);
            }        
          });
        });
      });
  </script>
  </body>
</html>