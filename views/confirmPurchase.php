<body class="Site"> <!--These two classes are needed for the footer to stick to the bottom of the page-->
<main class="Site-content">

<div id="additional-info" style="padding:0;height: 70px;">
    <div class="row" style="padding:0;height: 70px;">
        <div class="large-12 columns" style="padding:0;height: 70px;">
            <h1 class="color-white headings text-center">Confirmación de Compra</h2>
        </div>
    </div>
    
</div>
<div style="display: inline-block;border-style: none" id="intro">

<div class="wrapper" style="border:none">
    <ul class="pricing-table">
        <li class="title">Enterprise</li>
        <li class="price">$99.99</li>
        <li class="description">An awesome description</li>
        <li>42 Rad Features</li>
        <li>7GB of Power</li>
        <li><a class="button" href="#">Buy Now</a></li>
    </ul>
</div>

<?php require VIEWS_PATH."FooterUserView.php";?>

<style>

.pricing-table {
  background-color: #fefefe;
  border: solid 1px #cacaca;
  width: 100%;
  text-align: center;
  list-style-type: none;
}

.pricing-table li {
  border-bottom: dotted 1px #cacaca;
  padding: 0.875rem 1.125rem;
}

.pricing-table li:last-child {
  border-bottom: 0;
}

.pricing-table .title {
  background-color: #0a0a0a;
  color: #fefefe;
  border-bottom: 0;
}

.pricing-table .price {
  background-color: #e6e6e6;
  font-size: 2rem;
  border-bottom: 0;
}

.pricing-table .description {
  color: #8a8a8a;
  font-size: 80%;
}

.pricing-table :last-child {
  margin-bottom: 0;
}


</style>