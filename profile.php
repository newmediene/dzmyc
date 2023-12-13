<?php try{ require_once 'config.php'; session_start();

$checkUsers = $database->prepare("SELECT * FROM dzmyc_users WHERE ACTIVATED = '1' AND ID = :ID ");
$checkUsers->bindparam("ID",$_GET['id']);
$checkUsers->execute();

if(isset($_GET['id']) AND intval($_GET['id']) AND is_numeric($_GET['id']) AND $checkUsers->rowCount() > 0 ){ 

    // ADMIN
    $toDoAdmin = $database->prepare("SELECT * FROM dzmyc_admin WHERE ID = '1' ");
    $toDoAdmin->execute();
    $Admin = $toDoAdmin->fetchObject();
    
    // USERS
    $toDoUsers = $database->prepare("SELECT * FROM dzmyc_users WHERE ID = :ID ");
    $userID = intval($_GET['id']);
    $toDoUsers->bindparam("ID",$userID);
    $toDoUsers->execute();
    $Users = $toDoUsers->fetchObject(); ?>

<!DOCTYPE html>
<html>
    <head>
    
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1" >

        <link rel="stylesheet" href="style/home.css">
		<?php echo $Admin->CODEADSENSE ?>
        <link rel="icon" href="files/logo/icon.png" >
        <link rel="shortcut" href="files/logo/icon.png" >
        <link rel="apple-touch-icon" href="files/logo/icon.png" >
		
		<title>الرئيسية - <?php echo $Admin->SITENAME ?></title>
		<meta name="description" content="<?php echo $Admin->DESCRIPTION ?>"/>
        <meta name="author" content="newmediene" />
        
        <meta property="og:title" content="الرئيسية - <?php echo $Admin->SITENAME ?>" />
        <meta property="og:description" content="<?php echo $Admin->DESCRIPTION ?>" />
        <meta property="og:image" content="<?php echo $Admin->PICTURELOGO ?>" />
        <meta property="og:image:width" content="1200" />  
        <meta property="og:image:height" content="630"/>
        <meta property="og:type" content="website" />
        
    </head>

    <body>

        <div id="content" >

            <?php require_once 'navbar.php'; ?>

        <?php if($Users->ACTIVATED == true){ ?>
            
            <div class="bigImg" >
                <img src="files/class/class.jpg" >
                <p><?php echo $Users->NAME ?> <span style="font-size:8pt;" >[ <?php echo date("Y") - $Users->AGE ?> سنة ]</span> </p>
            </div>
            
            <?php if($Users->SEX == "MAN"){echo"<style>.bigImg p{background-color: #054c5e60;}</style>";}elseif($Users->SEX == "WOMAN"){echo"<style>.bigImg p{background-color: #c72b2b60;}</style>";} ?>
            
            <div class="profile" >
                <?php if($Users->PROFILE === ""){$RusProfile = "img/profile.png";}else{$RusProfile = $Users->PROFILE;} ?>
                <img src="<?php echo $RusProfile ?>" >
            </div><!--- profile --->

            <div class="topic" style="margin: 20px 0px;" ><p><?php echo $Users->TEXT ?></p></div><!--- topic --->


            <div class="list" >

                <?php if($Users->DATEUP != "" ){ ?><p><img src="img/date.png" ><?php echo $Users->DATEUP ?></p><?php } ?>

                <?php if($Users->WORKS != "" ){ ?><p><img src="img/worker.png" ><?php echo $Users->WORKS ?></p><?php } ?>

                <?php if($Users->ADDRESS != "" ){ ?><address><img src="img/home.png" ><?php echo $Users->ADDRESS ?></address><?php } ?>

                <?php if($Users->FCB != "" ){ ?><a href="<?php echo $Users->FCB ?>" ><img src="img/fcb.png" >صفحة الفيسبوك !</a><?php } ?>

                <p><img src="img/view.png" ><?php echo $Users->VIEW ?></p>

                <?php if($Users->PHONE != "" ){ ?><a href="tel:<?php echo $Users->PHONE ?>" dir="ltr" ><img src="img/tel.png" ><?php echo $Users->PHONE ?></a><?php } ?>

            </div><!--- infolist --->

            <?php }else{ echo "<div class='error'>تم تعطيل العضو ".$Users->NAME." !</div>"; }// --- ?>            
                
            <?php require_once 'home.php'; ?>

        </div><!--- content --->

        <?php require_once 'footer.php'; ?>           
    
<?php 
    }else{
        header("location:index.php",true);

    }
    
}catch (PDOException $error_msql) {require_once 'function.php'; error_mysql($error_msql);} $database = null ?>