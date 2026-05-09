<!DOCTYPE html>
<html lang="ar">

<head>
    <title>لوحة تحكم المشرف</title>
</head>

<body>

    <header>
    </header>

    <main>
        <?php 
          //after adding a new region successfully the dashboard page should be opened with a massge
          if (isset($_GET['success']) $$ $_['success']==1){
            echo '<div  id="alert-success" class="alert">تمت الاضافة بنجاح!</div>';
          }
        ?>
    </main>

    <footer>  
    </footer>

</body>

</html>