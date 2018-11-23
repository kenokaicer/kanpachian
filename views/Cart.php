<body class="Site"> <!--These two classes are needed for the footer to stick to the bottom of the page-->
<main class="Site-content">

<div id="additional-info" style="padding:0;height: 70px;">
    <div class="row" style="padding:0;height: 70px;">
        <div class="large-12 columns" style="padding:0;height: 70px;">
            <h1 class="color-white headings text-center">Carrito</h2>
        </div>
    </div>
    
</div>
<div style="display: inline-block;border-style: none" id="intro">
    <div class="wrapper" style="border-style: none;min-height:57.2vh;">
        <table style="border: 1px solid black;box-shadow:1px 2px 3px 5px hsl(0, 0%, 80%);">
            <th style="border-color: black;border-style: solid;border-width: 1px 1px 2px 1px;">Item</th>
            <th width="30%" style="border-color: black;border-style: solid;border-width: 1px 1px 2px 1px;">Evento</th>
            <th style="border-color: black;border-style: solid;border-width: 1px 1px 2px 1px;">Categoría</th>
            <th style="border-color: black;border-style: solid;border-width: 1px 1px 2px 1px;">Teatro</th>
            <th style="border-color: black;border-style: solid;border-width: 1px 1px 2px 1px;">Asiento</th>
            <th style="border-color: black;border-style: solid;border-width: 1px 1px 2px 1px;">Fecha</th>
            <th style="border-color: black;border-style: solid;border-width: 1px 1px 2px 1px;">Precio</th>
            <th style="border-color: black;border-style: solid;border-width: 1px 1px 2px 1px;">Borrar</th>
            <?php
            $i=0;
            $total=0;
            if(isset($purchaseLines)){
                foreach ($purchaseLines as $purchaseLine) {
                    $i++
            ?>
            <tr>
                <td><?=$i?></td>
                <td><?=$purchaseLine->getSeatsByEvent()->getEventByDate()->getEvent()->getEventName()?></td>
                <td><?=$purchaseLine->getSeatsByEvent()->getEventByDate()->getEvent()->getCategory()->getCategoryName()?></td>
                <td><?=$purchaseLine->getSeatsByEvent()->getEventByDate()->getTheater()->getTheaterName()?></td>
                <td><?=$purchaseLine->getSeatsByEvent()->getSeatType()->getSeatTypeName()?></td>
                <td style="text-align:center"><?=$purchaseLine->getSeatsByEvent()->getEventByDate()->getDate()?></td>
                <td style="text-align:center">$<?=$purchaseLine->getPrice()?></td>
                <td>
                <form method="post">
                    <input type="hidden" name="indexPurchaseLine" value="<?=$i-1?>">
                    <input type="submit" value="Borrar" formaction="<?=FRONT_ROOT?>Purchase/removePurchaseLine">
                </form>
                </td>
            </tr>
            <?php
            $total += $purchaseLine->getPrice();
                }
            }
            ?>
            <tr>
                <td colspan="7" style="text-align:right;font-weight:bold;border-color: black;border-style: solid;border-width: 2px 1px 1px 1px;">Total</td>
                <td style="text-align:center;font-weight:bold;border-color: black;border-style: solid;border-width: 2px 1px 1px 1px;">$<?=$total?></td>
            </tr>
        </table>
        <form method="get">
        <button type="submit" formaction="<?=FRONT_ROOT?>Purchase/confirmPurchase" <?php if($i==0) echo "disabled" ?>>Comprar</button>
        <button type="submit" formaction="<?=FRONT_ROOT?>Home/index">Volver</button>
        </form>

    </div>
</div>

<?php require VIEWS_PATH."FooterUserView.php";?>

<style>
table {
  
  border-spacing: 1;
  border-collapse: collapse;
  background: white;
  border-radius: 10px;
  overflow: hidden;

}



</style>
<script>
    $('#cart-number').attr("data-count","<?php echo sizeof($_SESSION["virtualCart"])?>"); //set proper cart lenght, as navbar is loaded before the new purchaseLine
</script>


