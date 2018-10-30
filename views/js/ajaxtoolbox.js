<script>

function send(data)
{
	 var jsonAr = JSON.stringify(data);
	    $.ajax({ url: 'testy',
         data: {action: jsonAr},
         type: 'post',
         success: function(output) {
                      alert(output);
                  }
    });
}

</script>