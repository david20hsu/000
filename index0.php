<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap 4 Navbar with Logo Image</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css" />

    <style>
    @charset "UTF-8";
    @import url(https://fonts.googleapis.com/earlyaccess/notosanstc.css);

    /* google雲端字體 思源黑體*/
    body {
        font-family: "Noto Sans TC", sans-serif;
        /* google雲端字體 思源黑體*/
        background-color: #fff;
        /*白色*/
        margin: 0;
        -webkit-text-size-adjust: 100%;
    }

    body a {
        color: white;

    }

    color #footer {
        color: white;
    }

    #footer a {
        color: white;
    }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-info ">
        <a class="navbar-brand" href="#">嘉南羊乳</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li
                    <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'class=" nav-item active"' : 'class=nav-item active'; ?>>
                    <a class="nav-link" href=" index.php">Home</a>
                </li>

                <li
                    <?php echo (basename($_SERVER['PHP_SELF']) == 'index0.php') ? 'class=" nav-item active"' : 'class=nav-item active'; ?>>
                    <a class="nav-link" href=" index.php">Home0</a>
                </li>


                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Dropdown
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#">Disabled</a>
                </li>

            </ul>
            <!--
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
    -->

        </div>

    </nav>
</body>

</html>