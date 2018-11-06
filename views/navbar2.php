<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="<?=CSS_PATH?>bootstrap_min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    
</head>
<body>

<nav class="navbar navbar-expand navbar-dark bg-dark" style="height:50px">
    <a href="<?=FRONT_ROOT?>Home" class="navbar-brand">Home</a>
    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <a href="#" class="nav-item active nav-link">Quem somos</a>
            <a href="#" class="nav-item active nav-link">Nossa história</a>
            <a href="#" class="nav item active nav-link">Localidades</a>

            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle active" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Nossos serviços</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a href="#" class="dropdown-item">Divisão Militar</a>
                    <a href="#" class="dropdown-item">Divisão Corporativa</a>
                    <a href="#" class="dropdown-item">Aplicações para a área de saúde</a>
                </div>
            </li>
        </ul>

        <form action="#" method="post" class="form-inline my-2 mylg-0">
            <input type="search" name="buscar" id="buscar" class="form-control mr-sm-2" placeholder="Buscar em todo o site" aria-label="Buscar">
            <button class="btn btn-outline-success" type="submit">Pesquisar</button>
        </form>
    </div>
</nav>
 
</body>
</html>