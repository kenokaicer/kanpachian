<?php
$regstration = $_POST['registration'];


    
    echo json_encode($regstration);

?>

<script>
function CallPHPFunction2(controller,method,value) //llamar al metodo y pasa parametro/s por GET
{
    $.ajax({
    url: controller+"/"+method+"?value="+value,
    type: 'post',
    dataType : 'script',
    data: {name: "lalilulelo",registration: "success"},
    success: function(data) {
        console.log(data);
        alert(data);
    },
})
}
</script>